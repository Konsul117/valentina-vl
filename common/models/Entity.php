<?php

namespace common\models;


use yii\db\ActiveRecord;

/**
 * Справочник сущностей
 *
 * @property int    $id           Уникальный идентификатор
 * @property string $title        Название
 * @property string $entity_class Класс реализации модели сущности
 */
class Entity extends ActiveRecord {

	const ATTR_ID = 'id';
	const ATTR_TITLE = 'title';
	const ATTR_ENTITY_CLASS = 'entity_class';

	/** Сущность - изображение */
	const ENTITY_BLOG_POST_ID = 1;

	/** Сущность - страница */
	const ENTITY_PAGE_ID = 2;

	/**
	 * Получить модель сущности, на которую указывают данные справочника
	 *
	 * @param int $entityId Идентификатор модели
	 * @param int $entityItemId Идентификатор строки
	 *
	 * @return ActiveRecord|null Объект модели, либо null, если данные по сущности не найдены
	 */
	public static function getEntityModel($entityId, $entityItemId) {
		/** @var static $entity */
		$entity = static::findOne($entityId);

		if ($entity === null) {
			return null;
		}

		if (!class_exists($entity->entity_class)) {
			return null;
		}

		$class = $entity->entity_class;
		/** @var ActiveRecord $model */
		$model = new $class();

		if (!$model instanceof ActiveRecord) {
			return null;
		}

		/** @var ActiveRecord $object */
		$object = $model->findOne($entityItemId);

		return $object;
	}
}