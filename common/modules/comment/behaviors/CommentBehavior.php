<?php

namespace common\modules\comment\behaviors;

use common\modules\comment\models\Comment;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Поведение комментариев для сущности
 * Присоединяется к сущности, у которой есть комментарии
 */
class CommentBehavior extends Behavior {

	/**
	 * Id сущности
	 *
	 * @see Entity
	 *
	 * @var int
	 */
	public $entityId;

	public function init() {
		parent::init();

		if ($this->entityId === null) {
			throw new InvalidConfigException('Не указан параметр entityId');
		}
	}

	public function events() {
		return [
			ActiveRecord::EVENT_AFTER_DELETE => [$this, 'afterDelete'],
		];
	}

	/**
	 * Обработка события удаления записи сущности
	 *
	 * @param Event $event
	 */
	public function afterDelete(Event $event) {
		/** @var ActiveRecord $entityModel */
		$entityModel = $event->sender;

		if (!$entityModel instanceof ActiveRecord) {
			return;
		}

		Comment::deleteAll([
			Comment::ATTR_RELATED_ENTITY_ID      => $this->entityId,
			Comment::ATTR_RELATED_ENTITY_ITEM_ID => $entityModel->primaryKey,
		]);
	}

}