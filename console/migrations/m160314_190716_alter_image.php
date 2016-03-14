<?php

use yii\db\Schema;
use yii\db\Migration;

class m160314_190716_alter_image extends Migration {

	var $tableName = 'image';

	public function safeUp() {
		$this->addColumn($this->tableName, 'title', Schema::TYPE_STRING . '(255) COMMENT "Название изображения" AFTER `related_entity_item_id`');
	}

	public function safeDown() {
		$this->dropColumn($this->tableName, 'title');
	}
}
