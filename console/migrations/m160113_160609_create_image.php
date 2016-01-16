<?php

use yii\db\Schema;
use yii\db\Migration;

class m160113_160609_create_image extends Migration {

	var $tableName = 'image';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'id' => $this->primaryKey() . ' COMMENT "Уникальынй идентификатор изображения"',
//			'related_entity_id' => Schema::TYPE_INTEGER . ' NULL COMMENT "Идентификатр сущности, с которой связано изображение"',
			'related_entity_item_id' => Schema::TYPE_INTEGER . ' NULL COMMENT "Идентификатр объекта сущности, с которой связано изображение"',
		], ' COMMENT "Изображения"');

//		$this->createIndex('ix-' . $this->tableName . '-[related_entity_id,related_entity_item_id]', $this->tableName, ['related_entity_id', 'related_entity_item_id']);
		$this->createIndex('ix-' . $this->tableName . '-[related_entity_id,related_entity_item_id]', $this->tableName, ['related_entity_item_id']);
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
