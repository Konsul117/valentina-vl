<?php

namespace frontend\modules\blogFront\widgets;

use common\modules\blog\models\BlogPost;
use yii\base\Widget;
use yii\bootstrap\BootstrapAsset;
use yii\data\Pagination;

class PostsWidget extends Widget {

	public $postsForPage = 10;

	public function run() {

		BootstrapAsset::register($this->getView());

		$query = BlogPost::find()->where([BlogPost::ATTR_IS_PUBLISHED => true]);

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

}