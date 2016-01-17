<?php

namespace frontend\modules\blogFront\controllers;

use frontend\modules\blogFront\BlogFront;
use Yii;
use yii\web\Controller;

class PostsController extends Controller {

	public function actionIndex() {
		/** @var BlogFront $blogFrontModule */
		$blogFrontModule = Yii::$app->modules['blogFront'];

		return $this->render('index', [
			'postsWidget' => $blogFrontModule->getPostsWidget(2),
		]);
	}

	public function actionView($title_url) {
		return $title_url;
	}

}