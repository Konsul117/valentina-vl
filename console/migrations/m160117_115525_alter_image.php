<?php

use yii\db\Migration;

class m160117_115525_alter_image extends Migration {

	var $tableName = 'image';

	public function safeUp() {
		$this->addColumn($this->tableName, 'is_main', 'tinyint NOT NULL DEFAULT 0 COMMENT "Главная картинка" AFTER `related_entity_item_id`');

		$this->createIndex('ix-' . $this->tableName . '-[is_main]', $this->tableName, ['is_main']);
	}

	public function safeDown() {
		$this->dropColumn($this->tableName, 'is_main');
	}
}
