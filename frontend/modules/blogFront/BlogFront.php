<?php

namespace frontend\modules\blogFront;

use common\modules\blog\Blog;
use common\modules\blog\models\BlogCategory;
use common\modules\blog\models\BlogPost;
use frontend\modules\blogFront\widgets\PostsWidget;
use yii\base\InvalidParamException;

class BlogFront extends Blog {

	/**
	 * @param int    $limit
	 * @param string $categoryUrl
	 * @return PostsWidget
	 * @throws InvalidParamException
	 */
	public function getPostsWidget($limit = null, $categoryUrl = null) {

		$query = BlogPost::find()->where([BlogPost::ATTR_IS_PUBLISHED => true]);

		if ($categoryUrl !== null) {
			$query->innerJoin(BlogCategory::tableName(),
				BlogCategory::tableName() . '.' . BlogCategory::ATTR_ID . '=' . BlogPost::tableName() . '.' . BlogPost::ATTR_CATEGORY_ID
				. ' AND ' . BlogCategory::tableName() . '.' . BlogCategory::ATTR_TITLE_URL . ' = :categoryUrl',
				[':categoryUrl' => $categoryUrl]);
		}

		$widget = new PostsWidget([
			'postsForPage' => $limit,
			'query'        => $query,
		]);

		if ($categoryUrl !== null && !$widget->checkCategory()) {
			throw new InvalidParamException('Некорректная категория');
		}

		return $widget;
	}

}