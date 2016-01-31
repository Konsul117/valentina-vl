<?php

namespace common\modules\blog\models;

use common\components\behaviors\SeoTranslitBehavior;
use common\components\behaviors\TimestampUTCBehavior;
use yii\db\ActiveRecord;

/**
 * Теги
 *
 * @property int        $id              Уникальный идентификатр тега
 * @property string     $name            Тег
 * @property string     $name_url        ЧПУ тега
 * @property string     $insert_stamp    Дата-время создания тега
 *
 * Отношения
 * @property BlogPost[] $blogPosts       Посты блога, связанные с тегом
 */
class BlogTag extends ActiveRecord {

	/** никальный идентификатр тега */
	const ATTR_ID = 'id';

	/** Тег */
	const ATTR_NAME = 'name';

	/** ЧПУ тега */
	const ATTR_NAME_URL = 'name_url';

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
			[
				'class'         => SeoTranslitBehavior::class,
				'attributeFrom' => static::ATTR_NAME,
				'attributeTo'   => static::ATTR_NAME_URL,
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['name', 'name_url'], 'unique'],
		];
	}
}