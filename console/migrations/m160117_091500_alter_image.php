<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_091500_alter_image extends Migration {

	var $tableName = 'image';

	public function safeUp() {
		$this->addColumn($this->tableName, 'insert_stamp', Schema::TYPE_DATETIME . ' NOT NULL DEFAULT "1970-01-01" COMMENT "Дата-время создания изображения"');
	}

	public function safeDown() {
		$this->dropColumn($this->tableName, 'insert_stamp');
	}
}
