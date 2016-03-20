<?php

namespace common\modules\blog\models;

use yii\db\ActiveRecord;

/**
 * Категории блога.
 *
 * @property int    $id                  Уникальный идентификатор категории
 * @property string $title               Название категории
 * @property string $title_url           Название ЧПУ категории
 * @property string $meta_title          Meta-title для страницы-категории
 * @property string $meta_description    Meta-description для страницы-категории
 */
class BlogCategory extends ActiveRecord {

	const ATTR_ID               = 'id';
	const ATTR_TITLE            = 'title';
	const ATTR_TITLE_URL        = 'title_url';
	const ATTR_META_TITLE       = 'meta_title';
	const ATTR_META_DESCRIPTION = 'meta_description';
	const SCENARIO_ADMIN = 'admin';

	public function attributeLabels() {
		return [
			static::ATTR_ID               => '№',
			static::ATTR_TITLE            => 'Название',
			static::ATTR_TITLE_URL        => 'ЧПУ',
			static::ATTR_META_TITLE       => 'Мета-заголовок',
			static::ATTR_META_DESCRIPTION => 'Мета-описание',
		];
	}

	public function scenarios() {
		return [
			static::SCENARIO_ADMIN => [
				static::ATTR_ID,
				static::ATTR_TITLE,
				static::ATTR_TITLE_URL,
				static::ATTR_META_TITLE,
				static::ATTR_META_DESCRIPTION,
			],
		];
	}

}