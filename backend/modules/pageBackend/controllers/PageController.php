<?php

namespace backend\modules\pageBackend\controllers;

use backend\base\BackendController;
use backend\modules\pageBackend\models\PageForm;
use backend\modules\pageBackend\models\PageSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер страниц
 */
class PageController extends BackendController {

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
		$searchModel  = new PageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setPagination(['pageSize' => 10]);
		$dataProvider->setSort(['defaultOrder' => [PageSearch::ATTR_INSERT_STAMP => SORT_DESC]]);

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Просмотр страницы
	 *
	 * @param int $id Id страницы
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionView($id) {
		/** @var PageForm $model */
		$model = PageForm::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Запись блога не найдена');
		}

		return $this->render('view', [
			'model' => $model,
		]);
	}

	/**
	 * Создание страницы
	 *
	 * @return string
	 */
	public function actionCreate() {
		$errors = [];

		$model = new PageForm();

		if (Yii::$app->request->isPost) {
			$errors = $this->pageSave($model);

			if (empty($errors)) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		}

		return $this->render('create', [
			'model'  => $model,
			'errors' => $errors,
		]);
	}

	/**
	 * Сохранение Страницы
	 *
	 * @param PageForm $model модель поста
	 * @return array ошибки
	 */
	protected function pageSave(PageForm $model) {
		$errors = [];

		$model->setScenario(PageForm::SCENARIO_UPDATE);

		//грузим форму в модель
		$model->load(Yii::$app->request->post());

		//собираем id всех добавленных картинок
		$newImagesIds = [];

		$uploadedImagesIds = Yii::$app->request->post('uploaded_images_ids');

		if (is_array($uploadedImagesIds) && !empty($uploadedImagesIds)) {
			foreach($uploadedImagesIds as $id) {
				$newImagesIds[] = (int) $id;
			}
		}

		$saveResult = $model->saveWithNewImages($newImagesIds);

		if (!$saveResult) {
			$errors[] = 'Ошибка при сохранении записи';
		}

		return $errors;
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
		/** @var PageForm $model */
		$model = PageForm::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Запись блога не найдена');
		}

		if (Yii::$app->request->isPost) {
			$errors = $this->pageSave($model);

			if (empty($errors)) {
				return $this->redirect(['view', 'id' => $model->id]);
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
		/** @var PageForm $model */
		$model = PageForm::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Запись блога не найдена');
		}

		$model->delete();

		return $this->redirect(['/pageBackend/page/index']);
	}

}