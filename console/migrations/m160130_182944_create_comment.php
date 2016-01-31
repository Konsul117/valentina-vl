<?php

use yii\db\Schema;
use yii\db\Migration;

class m160130_182944_create_comment extends Migration {

	var $tableName = 'comment';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'id'                     => $this->primaryKey() . ' COMMENT "Уникальный идентификатор"',
			'related_entity_id'      => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Идентификатор сущности, с которой связан комментарий"',
			'related_entity_item_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Идентификатор объекта сущности, с которой связан комментарий"',
			'nickname'               => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "Никнейм автора комментария"',
			'content'                => Schema::TYPE_TEXT . ' NOT NULL DEFAULT "" COMMENT "Содержание комментария"',
			'is_published'           => 'TINYINT NOT NULL DEFAULT 1 COMMENT "Состояние опубликованности комментария"',
			'insert_stamp'           => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Дата-время создания комментария"',
			'update_stamp'           => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Дата-время обновления комментария"',
		], 'COMMENT "Комментарии"');

		$this->createIndex(
			'ix-' . $this->tableName . '-[many]',
			$this->tableName,
			['related_entity_id', 'related_entity_item_id', 'is_published']
		);

		$this->createIndex('ix-' . $this->tableName . '-[insert_stamp]', $this->tableName, ['insert_stamp']);
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
