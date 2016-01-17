<?php

namespace frontend\controllers;

use frontend\modules\blogFront\BlogFront;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller {

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		/** @var BlogFront $blogFrontModule */
		$blogFrontModule = Yii::$app->modules['blogFront'];

		return $this->render('index', [
			'postsWidget' => $blogFrontModule->getPostsWidget(2),
		]);
	}
}
