<?php

use yii\db\Migration;

class m160211_155307_alter_image extends Migration {

	var $tableName = 'image';

	public function safeUp() {
		$this->addColumn($this->tableName, 'is_need_watermark', 'tinyint NOT NULL DEFAULT 1 COMMENT "Нужен водяной знак" AFTER `is_main`');
	}

	public function safeDown() {
		$this->dropColumn($this->tableName, 'is_need_watermark');
	}
}
