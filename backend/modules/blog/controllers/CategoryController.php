<?php

namespace backend\modules\blog\controllers;

use backend\base\BackendController;
use common\modules\blog\models\BlogCategory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * Админка категорий.
 */
class CategoryController extends BackendController {

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
	 * Список страниц
	 *
	 * @return string
	 */
	public function actionIndex() {
		$categoryModel = new BlogCategory();

		$query = $categoryModel->find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		$dataProvider->setPagination(['pageSize' => 10]);

		return $this->render('index', [
			'searchModel'  => $categoryModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Редактирование категории
	 *
	 * @param int $id Id страницы
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionUpdate($id) {
		/** @var BlogCategory $model */
		$model = BlogCategory::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Запись блога не найдена');
		}

		$model->setScenario($model::SCENARIO_ADMIN);

		if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}

		$errors = $model->getErrors();

		return $this->render('update', [
			'model'  => $model,
			'errors' => $errors,
		]);
	}
}