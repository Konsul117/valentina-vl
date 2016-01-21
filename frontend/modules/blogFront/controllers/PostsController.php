<?php

namespace frontend\modules\blogFront\controllers;

use common\modules\blog\models\BlogCategory;
use common\modules\blog\models\BlogPost;
use frontend\modules\blogFront\BlogFront;
use Yii;
use yii\base\Exception;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PostsController extends Controller {

	/**
	 * Модуль Блога для фронта
	 * @var BlogFront
	 */
	protected $blogFrontModule;

	public function init() {
		parent::init();
		$this->blogFrontModule = Yii::$app->modules['blogFront'];

		if ($this->blogFrontModule === null) {
			throw new Exception('Отсутствует модуль BlogFront');
		}
	}

	public function actionIndex() {
		return $this->render('index', [
			'postsWidget' => $this->blogFrontModule->getPostsWidget(1),
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

		return $this->render('category', [
			'postsWidget' => $this->blogFrontModule->getPostsWidget(2, $category_url),
			'category'    => $category,
		]);
	}

	public function actionSearch($query) {

		$query = Html::encode($query);

		return $this->render('search', [
			'postsWidget' => $this->blogFrontModule->getSearchPostsWidget($query),
		]);
	}

}