<?php

namespace frontend\modules\blogFront\widgets;

use common\modules\blog\models\BlogCategory;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\BootstrapAsset;
use yii\data\Pagination;
use yii\db\Query;

class PostsWidget extends Widget {

	/**
	 * Количество постов на одну страницу
	 *
	 * @var int
	 */
	public $postsForPage = 10;

	/**
	 * @var Query
	 */
	public $query;

	/**
	 * Категория фильтрации
	 *
	 * @var string
	 */
	public $categoryUrl;

	/**
	 * @inheritdoc
	 */
	public function run() {
		if (!$this->query instanceof Query) {
			throw new InvalidConfigException('Отсуствует query');
		}

		BootstrapAsset::register($this->getView());

		$countQuery = clone $this->query;

		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'pageSize'   => $this->postsForPage,
			'route'      => '/blogFront/posts/index',
		]);

		$posts = $this->query->offset($pages->offset)
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
	 *
	 * @return bool
	 */
	public function checkCategory() {
		if ($this->categoryUrl === null) {
			return false;
		}

		return !empty(BlogCategory::findOne([BlogCategory::ATTR_TITLE_URL => $this->categoryUrl]));
	}

}