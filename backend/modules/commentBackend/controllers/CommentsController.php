<?php

namespace backend\modules\commentBackend\controllers;

use backend\base\BackendController;
use backend\modules\commentBackend\models\CommentSearch;
use common\modules\comment\models\Comment;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер просмотра комментариев в бэкэнде
 */
class CommentsController extends BackendController {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Главная страница - список комментариев
	 *
	 * @return string
	 */
	public function actionIndex() {
		$searchModel  = new CommentSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$dataProvider->setPagination(['pageSize' => 10]);
		$dataProvider->setSort(['defaultOrder' => [CommentSearch::ATTR_INSERT_STAMP => SORT_DESC]]);

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Редактирование страницы
	 *
	 * @param int $id Id страницы
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionUpdate($id) {
		$errors = [];
		/** @var Comment $model */
		$model = Comment::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Комментарий не найден');
		}

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			
			if ($model->save()) {

				Yii::$app->session->addFlash('success', 'Комментарий успешно сохранён');

				return $this->redirect(['index']);
			}
			else {
				Yii::$app->session->addFlash('error', 'Ошибка при сохранении комментария');

				$errors = $model->getErrors();
			}
		}

		return $this->render('update', [
			'model'  => $model,
			'errors' => $errors,
		]);
	}

	/**
	 * Удаление страницы
	 *
	 * @param $id
	 *
	 * @return Response
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionDelete($id) {
		/** @var Comment $model */
		$model = Comment::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Запись блога не найдена');
		}

		if ($model->delete()) {
			Yii::$app->session->addFlash('success', 'Комментарий успешно удалён');
		}
		else {
			Yii::$app->session->addFlash('error', 'Ошибка при удалении комментария');
		}

		return $this->redirect(['index']);
	}

}