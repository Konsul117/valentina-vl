<?php

namespace frontend\modules\blogFront\widgets;

use common\modules\blog\models\BlogPost;
use yii\base\Widget;

/**
 * Виджет вывода тегов для поста
 */
class TagsPostWidget extends Widget {

	/** @var BlogPost */
	public $post;

	public function run() {
		return $this->render('tags-post', [
			'widget' => $this
		]);
	}

}