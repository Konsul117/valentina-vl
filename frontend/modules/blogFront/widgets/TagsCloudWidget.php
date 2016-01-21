<?php

namespace frontend\modules\blogFront\widgets;

use common\modules\blog\models\BlogTag;
use yii\base\Widget;

/**
 * Виджет - "Облако тегов"
 */
class TagsCloudWidget extends Widget {

	public function run() {

		$tags = BlogTag::find()
			->orderBy([BlogTag::ATTR_NAME => SORT_ASC])
			->all();

		return $this->render('tags-cloud',[
			'tags' => $tags,
		]);
	}

}