<?php

namespace frontend\modules\blogFront;

use common\modules\blog\Blog;
use frontend\modules\blogFront\widgets\PostsWidget;

class BlogFront extends Blog {

	/**
	 * @param int $limit
	 * @return PostsWidget
	 */
	public function getPostsWidget($limit = null) {
		return new PostsWidget(['postsForPage' => $limit]);
	}

}