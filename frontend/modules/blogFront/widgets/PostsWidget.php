<?php

namespace frontend\modules\blogFront\widgets;

use common\modules\blog\models\BlogCategory;
use common\modules\blog\models\BlogPost;
use yii\base\Widget;
use yii\bootstrap\BootstrapAsset;
use yii\data\Pagination;

class PostsWidget extends Widget {

	/**
	 * Количество постов на одну страницу
	 * @var int
	 */
	public $postsForPage = 10;

	/**
	 * Категория фильтрации
	 * @var string
	 */
	public $categoryUrl;

	/**
	 * @inheritdoc
	 */
	public function run() {

		BootstrapAsset::register($this->getView());

		$query = BlogPost::find()->where([BlogPost::ATTR_IS_PUBLISHED => true]);

		if ($this->categoryUrl !== null) {
			$query->innerJoin(BlogCategory::tableName(), BlogCategory::tableName() . '.' . BlogCategory::ATTR_ID . '=' . BlogPost::tableName() . '.' . BlogPost::ATTR_CATEGORY_ID
			. ' AND ' . BlogCategory::tableName() . '.' . BlogCategory::ATTR_TITLE_URL . ' = :categoryUrl', [':categoryUrl' => $this->categoryUrl]);
		}

		$countQuery = clone $query;

		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize' => $this->postsForPage,
			'route' => 'blog'
		]);

		$posts = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		return $this->render('posts-widget', [
			'posts'  => $posts,
			'pages'  => $pages,
			'widget' => $this,
		]);
	}

	/**
	 * Проверка корректности переданной категории
	 * @return bool
	 */
	public function checkCategory() {
		if ($this->categoryUrl === null) {
			return false;
		}

		return !empty(BlogCategory::findOne([BlogCategory::ATTR_TITLE_URL => $this->categoryUrl]));
	}

}