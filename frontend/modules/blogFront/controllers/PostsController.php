<?php

namespace frontend\modules\blogFront\controllers;

use frontend\modules\blogFront\BlogFront;
use Yii;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\HttpException;

class PostsController extends Controller {

	public function actionIndex() {
		/** @var BlogFront $blogFrontModule */
		$blogFrontModule = Yii::$app->modules['blogFront'];

		return $this->render('index', [
			'postsWidget' => $blogFrontModule->getPostsWidget(2),
		]);
	}

	public function actionView($title_url) {
		return 'view post ' . $title_url;
	}

	public function actionCategory($category_url) {
		/** @var BlogFront $blogFrontModule */
		$blogFrontModule = Yii::$app->modules['blogFront'];

		try {
			return $this->render('index', [
				'postsWidget' => $blogFrontModule->getPostsWidget(2, $category_url),
			]);
		}
		catch (InvalidParamException $e) {
			throw new HttpException(404, 'Запрошена неизвестная категория');
		}
	}

}