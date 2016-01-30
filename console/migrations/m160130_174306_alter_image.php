<?php

use yii\db\Schema;
use yii\db\Migration;

class m160130_174306_alter_image extends Migration {

	var $tableName = 'image';

	public function safeUp() {
		$this->addColumn($this->tableName, 'related_entity_id', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 COMMENT "Идентификатор сущности, с которой связано изображение" AFTER `id`');
		$this->createIndex('ix-' . $this->tableName . '-[related_entity_id,related_entity_item_id,is_main]', $this->tableName, ['related_entity_id', 'related_entity_item_id','is_main']);
		$this->dropIndex('ix-' . $this->tableName . '-[related_entity_item_id,is_main]', $this->tableName);
	}

	public function safeDown() {
		$this->dropIndex('ix-' . $this->tableName . '-[related_entity_id,related_entity_item_id,is_main]', $this->tableName);
		$this->dropColumn($this->tableName, 'related_entity_id');
		$this->createIndex('ix-' . $this->tableName . '-[related_entity_item_id,is_main]', $this->tableName, ['related_entity_item_id','is_main']);
	}

}
