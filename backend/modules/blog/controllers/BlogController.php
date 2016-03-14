<?php

namespace backend\modules\blog\controllers;

use backend\base\BackendController;
use backend\modules\blog\models\BlogPostForm;
use backend\modules\blog\models\BlogPostSearch;
use common\modules\blog\models\BlogCategory;
use common\modules\image\models\Image;
use Yii;
use yii\caching\TagDependency;
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

		$saveResult = $model->saveWithNewImages($newImagesIds);

		if (!$saveResult) {
			$errors[] = 'Ошибка при сохранении записи';
		}

		$this->processImagesParams($model);

		return $errors;
	}

	/**
	 * Обработка параметров изображений после поста страницы.
	 *
	 * @param BlogPostForm $model
	 */
	protected function processImagesParams(BlogPostForm $model) {
		//обрабаытваем изображения

		//флаги "Watermark"
		$needWatermarkItems = Yii::$app->request->post('needWatermark', []);
		//имена изображений
		$imageTitles = Yii::$app->request->post('image-title', []);

		//получаем все загруженные изображения
		$images = $model->images;

		//и далее обрабатыаем
		//было ли обновлено любое изображение
		$isUpdatedAnyImage = false;

		foreach ($images as $image) {
			//нужно ли сохранять текущее изображение
			$needCurrentSave = false;

			if (isset($needWatermarkItems[$image->id])) {
				if ((bool)$image->is_need_watermark !== (bool)$needWatermarkItems[$image->id]) {
					$image->is_need_watermark = (bool)$needWatermarkItems[$image->id];

					$needCurrentSave = true;

					$image->clearThumbs();
				}
			}

			if (isset($imageTitles[$image->id])) {
				if ($image->title !== $imageTitles[$image->id]) {
					$image->title = $imageTitles[$image->id];
					$needCurrentSave     = true;
				}
			}

			if ($needCurrentSave) {
				$image->save();
				$isUpdatedAnyImage = true;
			}
		}

		if ($isUpdatedAnyImage) {
			TagDependency::invalidate(Yii::$app->cache, Image::class);
		}
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

		$result = $model->delete();

		if ($result) {
			Yii::$app->session->addFlash('success', 'Пост успешно удалён');
		}
		else {
			Yii::$app->session->addFlash('error', 'Ошибка при удалении поста');
		}

		return $this->redirect(['/blog/blog/category/', 'category_url' => $model->category->title_url]);
	}

}