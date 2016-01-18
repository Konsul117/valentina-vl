<?php

namespace frontend\modules\blogFront\controllers;

use common\modules\blog\models\BlogCategory;
use common\modules\blog\models\BlogPost;
use frontend\modules\blogFront\BlogFront;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PostsController extends Controller {

	public function actionIndex() {
		/** @var BlogFront $blogFrontModule */
		$blogFrontModule = Yii::$app->modules['blogFront'];

		return $this->render('index', [
			'postsWidget' => $blogFrontModule->getPostsWidget(2),
		]);
	}

	public function actionView($title_url) {
		/** @var BlogPost $post */
		$post = BlogPost::findOne([BlogPost::ATTR_TITLE_URL => $title_url]);

		if ($post === null) {
			throw new NotFoundHttpException('Запись не найдена');
		}

		return $this->render('view', [
			'post' => $post,
		]);
	}

	public function actionCategory($category_url) {
		/** @var BlogCategory $category */
		$category = BlogCategory::findOne([BlogCategory::ATTR_TITLE_URL => $category_url]);

		if ($category === null) {
			throw new NotFoundHttpException('Некорректная категория');
		}

		/** @var BlogFront $blogFrontModule */
		$blogFrontModule = Yii::$app->modules['blogFront'];

		return $this->render('category', [
			'postsWidget' => $blogFrontModule->getPostsWidget(2, $category_url),
			'category'    => $category,
		]);
	}

}