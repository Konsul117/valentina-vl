<?php

namespace frontend\controllers;

use common\modules\page\models\Page as PageModel;
use common\modules\page\Page;
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
		$blogFrontModule = null;
		$postsWidget = null;
		if (isset(Yii::$app->modules['blogFront'])) {
			$blogFrontModule = Yii::$app->modules['blogFront'];

			$postsWidget                 = $blogFrontModule->getPostsWidget();
			$postsWidget->showEmptyLabel = false;
		}

		$mainPage = null;

		/** @var Page $pageModule */
		$pageModule = null;

		if (isset(Yii::$app->modules['pageFront'])) {
			$pageModule = Yii::$app->modules['pageFront'];

			$mainPage = $pageModule->getPageById(PageModel::PAGE_ID_MAIN);
		}

		return $this->render('index', [
			'postsWidget' => $postsWidget,
			'mainPage'    => $mainPage,
		]);
	}
}
