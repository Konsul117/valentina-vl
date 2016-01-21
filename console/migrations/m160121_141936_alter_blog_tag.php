<?php

use yii\db\Schema;
use yii\db\Migration;

class m160121_141936_alter_blog_tag extends Migration {

	var $tableName = 'blog_tag';

	public function safeUp() {
		$this->addColumn($this->tableName, 'name_url', Schema::TYPE_STRING . '(70) NOT NULL DEFAULT "" COMMENT "ЧПУ тега" AFTER `name`');
		$this->createIndex('ux-' . $this->tableName . '-[name]', $this->tableName, ['name'], true);
		$this->createIndex('ux-' . $this->tableName . '-[name_url]', $this->tableName, ['name_url'], true);
	}

	public function safeDown() {
		$this->dropColumn($this->tableName, 'name_url');
		$this->dropIndex('ux-' . $this->tableName . '-[name]', $this->tableName);
	}
}
