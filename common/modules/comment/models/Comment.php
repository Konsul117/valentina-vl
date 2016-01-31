<?php

namespace common\modules\comment\models;

use common\components\TimestampUTCBehavior;
use yii\db\ActiveRecord;

/**
 * Комментарии
 *
 * @property int    $id                     Уникальный идентификатор
 * @property int    $related_entity_id      Идентификатор сущности, с которой связан комментарий
 * @property int    $related_entity_item_id Идентификатор объекта сущности, с которой связан комментарий
 * @property string $nickname               Никнейм автора комментария
 * @property string $content                Содержание комментария
 * @property bool   $is_published           Состояние опубликованности комментария
 * @property string $insert_stamp           Дата-время создания комментария
 * @property string $update_stamp           Дата-время обновления комментария
 */
class Comment extends ActiveRecord {

	/** Уникальынй идентификатор изображения */
	const ATTR_ID = 'id';

	/** Идентификатор сущности, с которой связан комментарий */
	const ATTR_RELATED_ENTITY_ID = 'related_entity_id';

	/** Идентификатр объекта сущности, с которой связан комментарий */
	const ATTR_RELATED_ENTITY_ITEM_ID = 'related_entity_item_id';

	/** Никнейм автора комментария */
	const ATTR_NICKNAME = 'nickname';

	/** Содержание комментария */
	const ATTR_CONTENT = 'content';

	/** Состояние опубликованности */
	const ATTR_IS_PUBLISHED = 'is_published';

	/** Дата-время создания комментария */
	const ATTR_INSERT_STAMP = 'insert_stamp';

	/** Дата-время обновления комментария */
	const ATTR_UPDATE_STAMP = 'update_stamp';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampUTCBehavior::class,
				'createdAtAttribute' => static::ATTR_INSERT_STAMP,
				'updatedAtAttribute' => static::ATTR_UPDATE_STAMP,
			],
		];
	}

	public function rules() {
		return [
			[[static::ATTR_IS_PUBLISHED], 'default', 'value' => true],
			[[static::ATTR_NICKNAME, static::ATTR_CONTENT], 'safe'],
			[[static::ATTR_NICKNAME, static::ATTR_CONTENT], 'required'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID           => '№',
			static::ATTR_NICKNAME     => 'Имя',
			static::ATTR_CONTENT      => 'Комментарий',
			static::ATTR_IS_PUBLISHED => 'Опубликован',
			static::ATTR_INSERT_STAMP => 'Создано',
			static::ATTR_UPDATE_STAMP => 'Обновлено',
		];
	}

}