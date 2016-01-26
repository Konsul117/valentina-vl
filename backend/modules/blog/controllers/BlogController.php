<?php

namespace backend\modules\blog\controllers;

use backend\base\BackendController;
use backend\modules\blog\models\BlogPostForm;
use backend\modules\blog\models\BlogPostSearch;
use common\models\Image;
use common\modules\blog\models\BlogCategory;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BlogController extends BackendController {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'verbs'	 => [
				'class'		 => VerbFilter::className(),
				'actions'	 => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Главная страница
	 * @return string
	 */
	public function actionIndex() {
		return $this->render('index');
	}

	/**
	 * Страница категории
	 * @param $category_url
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionCategory($category_url) {
		/** @var BlogCategory $category */
		$category = BlogCategory::findOne(['title_url' => $category_url]);

		if ($category === null) {
			throw new NotFoundHttpException('Категория блога не найдена');
		}

		$searchModel  = new BlogPostSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $category->id);
		$dataProvider->setPagination(['pageSize' => 10]);
		$dataProvider->setSort(['defaultOrder' => [BlogPostSearch::ATTR_INSERT_STAMP => SORT_DESC]]);

		return $this->render('category', [
			'category'     => $category,
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Просмотр поста
	 *
	 * @param $id
	 * @return string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionView($id) {
		/** @var BlogPostForm $model */
		$model = BlogPostForm::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Запись блога не найдена');
		}

		return $this->render('view', [
			'model' => $model,
		]);
	}

	/**
	 * Создание поста
	 *
	 * @return string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionCreate($category_id) {
		$errors = [];

		$model = new BlogPostForm();
		$model->category_id = $category_id;

		if (Yii::$app->request->isPost) {
			$errors = $this->postSave($model);

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
	 * Сохранение поста
	 *
	 * @param BlogPostForm $model модель поста
	 * @return array ошибки
	 */
	protected function postSave(BlogPostForm $model) {
		$errors = [];

		$model->setScenario(BlogPostForm::SCENARIO_UPDATE);

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

		if ($model->isNewRecord) {
			//если модель новая
			//вешаем сохранение картинок на событие
			$model->on(BlogPostForm::EVENT_AFTER_INSERT, function() use ($newImagesIds, $model) {
				Image::bindImagesToRelated($newImagesIds, $model->id);
			});
		}
		else {
			Image::bindImagesToRelated($newImagesIds, $this->id);
		}

		$saveResult = $model->save();

		if (!$saveResult) {
			$errors[] = 'Ошибка при сохранении записи';
		}

		return $errors;
	}

	/**
	 * Редактирование поста
	 *
	 * @param $id
	 * @return string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionUpdate($id) {
		$errors = [];
		/** @var BlogPostForm $model */
		$model = BlogPostForm::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Запись блога не найдена');
		}

		if (Yii::$app->request->isPost) {
			$errors = $this->postSave($model);

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
	 * Удаление поста
	 *
	 * @param $id
	 * @return string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionDelete($id) {
		/** @var BlogPostForm $model */
		$model = BlogPostForm::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException('Запись блога не найдена');
		}

		$model->delete();

		return $this->redirect(['/blog/blog/category/', 'category_url' => $model->category->title_url]);
	}

}