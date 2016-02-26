<?php

namespace console\controllers;

use common\modules\sitemap\Sitemap;
use Yii;
use yii\console\Controller;

class SitemapGenController extends Controller {

	/**
	 * Генерация sitemap
	 */
	public function actionIndex() {
		/** @var Sitemap $sitemapModule */
		$sitemapModule = Yii::$app->modules['sitemap'];

		$dom = $sitemapModule->generateSitemap();

		file_put_contents(Yii::getAlias('@frontend/web') . DIRECTORY_SEPARATOR . 'sitemap.xml', $dom->saveXML());
	}
}