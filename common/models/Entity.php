<?php

namespace common\models;


use yii\db\ActiveRecord;

/**
 * Справочник сущностей
 *
 * @property int    $id    Уникальный идентификатор
 * @property string $title Название
 */
class Entity extends ActiveRecord {

	/** Уникальный идентификатор */
	const ATTR_ID = 'id';

	/** Название */
	const ATTR_TITLE = 'title';

	/** Сущность - изображение */
	const ENTITY_BLOG_POST_ID = 1;

	/** Сущность - страница */
	const ENTITY_PAGE_ID = 2;
}