<?php

use yii\db\Migration;

class m160118_131034_alter_image extends Migration {

	var $tableName = 'image';

	public function safeUp() {
		$this->createIndex('ix-' . $this->tableName . '-[related_entity_item_id,is_main]', $this->tableName, ['related_entity_item_id','is_main']);
		$this->createIndex('ix-' . $this->tableName . '-[insert_stamp]', $this->tableName, ['insert_stamp']);
	}

	public function safeDown() {
		$this->dropIndex('ix-' . $this->tableName . '-[related_entity_item_id,is_main]', $this->tableName);
		$this->dropIndex('ix-' . $this->tableName . '-[insert_stamp]', $this->tableName);
	}
}
