<?php

namespace frontend\modules\blogFront;

use common\modules\blog\Blog;
use frontend\modules\blogFront\widgets\PostsWidget;
use yii\base\InvalidParamException;

class BlogFront extends Blog {

	/**
	 * @param int $limit
	 * @param string $categoryUrl
	 * @return PostsWidget
	 * @throws InvalidParamException
	 */
	public function getPostsWidget($limit = null, $categoryUrl = null) {
		$widget = new PostsWidget([
			'postsForPage' => $limit,
			'categoryUrl'   => $categoryUrl,
		]);

		if ($categoryUrl !== null && !$widget->checkCategory()) {
			throw new InvalidParamException('Некорректная категория');
		}

		return $widget;
	}

}