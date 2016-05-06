<?php

namespace frontend\controllers;

use common\modules\sitemap\Sitemap;
use frontend\modules\blogFront\BlogFront;
use Yii;
use yii\web\Controller;
use yii\web\Response;

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
		$blogFrontModule = null;
		$postsWidget = null;
		if (isset(Yii::$app->modules['blogFront'])) {
			$blogFrontModule = Yii::$app->modules['blogFront'];

			$postsWidget                 = $blogFrontModule->getPostsWidget();
			$postsWidget->showEmptyLabel = false;
		}

		return $this->render('index', [
			'postsWidget' => $postsWidget,
		]);
	}

	public function actionSitemap() {
		/** @var Sitemap $sitemapModule */
		$sitemapModule = Yii::$app->modules['sitemap'];

		$dom = $sitemapModule->generateSitemap();

		Yii::$app->response->format = Response::FORMAT_RAW;

		Yii::$app->response->headers->add('Content-Type', 'text/xml');

		return $dom->saveXML();
	}
}
