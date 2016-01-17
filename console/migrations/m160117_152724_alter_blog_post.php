<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_152724_alter_blog_post extends Migration {

	var $tableName = 'blog_post';

	public function safeUp() {
		$this->addColumn($this->tableName, 'short_content', Schema::TYPE_TEXT . ' NOT NULL DEFAULT "" COMMENT "Краткое содержание" AFTER `title_url`');
	}

	public function safeDown() {
		$this->dropColumn($this->tableName, 'short_content');
	}
}
