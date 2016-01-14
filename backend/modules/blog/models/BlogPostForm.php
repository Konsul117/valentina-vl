<?php


namespace backend\modules\blog\models;


use common\modules\blog\models\BlogPost;

class BlogPostForm extends BlogPost {

	public static function tableName() {
		return BlogPost::tableName();
	}

	const SCENARIO_UPDATE = 'scenarioUpdate';

	public function scenarios() {
		return [
			static::SCENARIO_UPDATE => [
				static::ATTR_TITLE,
				static::ATTR_CONTENT,
				static::ATTR_TAGS,
				static::ATTR_IS_PUBLISHED,
			],
		];
	}

}