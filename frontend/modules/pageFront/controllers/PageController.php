<?php

namespace frontend\modules\pageFront\controllers;
use common\modules\page\models\Page;
use yii\web\NotFoundHttpException;

/**
 * Контроллер страниц для фронтэнда
 */
class PageController extends \yii\web\Controller {

	/**
	 * Просмотр страницы
	 *
	 * @param string $title_url ЧПУ страницы
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
	public function actionView($title_url) {
		/** @var Page $page */
		$page = Page::find()
			->where([
				Page::ATTR_TITLE_URL => $title_url,
				Page::ATTR_IS_PUBLISHED => 1,
			])
			->one();

		if ($page === null) {
			throw new NotFoundHttpException('Страница не найдена');
		}

		return $this->render('view', [
			'page' => $page,
		]);
	}

}