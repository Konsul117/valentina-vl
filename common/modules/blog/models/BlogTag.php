<?php

namespace common\modules\blog\models;

use common\components\TimestampUTCBehavior;
use yii\db\ActiveRecord;

/**
 * Теги
 *
 * @property int    $id              Уникальный идентификатр тега
 * @property string $name            Тег
 * @property string $insert_stamp    Дата-время создания тега
 */
class BlogTag extends ActiveRecord {

	/** никальный идентификатр тега */
	const ATTR_ID = 'id';

	/** Тег */
	const ATTR_NAME = 'name';

	/** Дата-время создания тега */
	const ATTR_INSERT_STAMP = 'insert_stamp';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampUTCBehavior::class,
				'createdAtAttribute' => static::ATTR_INSERT_STAMP,
				'updatedAtAttribute' => false,
			],
		];
	}

}