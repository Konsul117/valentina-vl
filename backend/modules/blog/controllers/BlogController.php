<?php

namespace backend\modules\blog\controllers;

use backend\base\BackendController;
use backend\modules\blog\models\BlogPostForm;
use backend\modules\blog\models\BlogPostSearch;
use common\exceptions\ModelSaveException;
use common\models\Image;
use common\modules\blog\models\BlogCategory;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class BlogController extends BackendController {

	public function actionIndex() {
		return $this->render('index');
	}

	public function actionCategory($category_url) {
		/** @var BlogCategory $category */
		$category = BlogCategory::findOne(['title_url' => $category_url]);

		if ($category === null) {
			throw new NotFoundHttpException('Категория блога не найдена');
		}

		$searchModel  = new BlogPostSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setPagination(['pageSize' => 10]);

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
	}

	/**
	 * Создание поста
	 *
	 * @return string|Response
	 * @throws NotFoundHttpException
	 */
	public function actionCreate() {
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

		$model->setScenario(BlogPostForm::SCENARIO_UPDATE);

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());

			$saveResult = $model->save();

			if ($saveResult) {
				$newImagesIds = [];

				$uploadedImagesIds = Yii::$app->request->post('uploaded_images_ids');

				if (is_array($uploadedImagesIds) && !empty($uploadedImagesIds)) {
					foreach($uploadedImagesIds as $id) {
						$newImagesIds[] = (int) $id;
					}
				}

				//.. получение ids

				try {
					Image::bindImagesToRelated($newImagesIds, $model->id);
				}
				catch (ModelSaveException $e) {
					$errors[] = $e->getMessage();
				}
			}
			else {
				$errors[] = 'Ошибка при сохранении записи';
			}

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
	}

}