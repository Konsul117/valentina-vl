<?php

namespace frontend\modules\commentFront\models;

use common\modules\comment\models\Comment;
use yii\helpers\ArrayHelper;

/**
 * Расшинерие модели комментария для фронэтнда
 */
class CommentFront extends Comment {

	const SCENARIO_USER_ADD = 'userAdd';

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return Comment::tableName();
	}

	public function scenarios() {
		return ArrayHelper::merge(parent::scenarios(), [
			static::SCENARIO_USER_ADD => [static::ATTR_NICKNAME, static::ATTR_CONTENT],
		]);
	}

	public function rules() {
		return ArrayHelper::merge(parent::rules(), [
			[[static::ATTR_NICKNAME, static::ATTR_CONTENT], 'filter', 'filter' => function($v) {
				return strip_tags($v);
			}],
		]);
	}

}