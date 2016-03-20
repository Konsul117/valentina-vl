<?php

use yii\db\Schema;
use yii\db\Migration;

class m160320_120655_alter_blog_category extends Migration {

	var $tableName = 'blog_category';

	public function safeUp() {
		$this->addColumn($this->tableName, 'meta_title', Schema::TYPE_STRING . '(255) COMMENT "Meta-title для страницы-категории" DEFAULT "" AFTER `title_url`');
		$this->addColumn($this->tableName, 'meta_description', Schema::TYPE_STRING . '(255) COMMENT "Meta-description для страницы-категории" DEFAULT "" AFTER `meta_title`');
	}

	public function safeDown() {
		$this->dropColumn($this->tableName, 'meta_title');
		$this->dropColumn($this->tableName, 'meta_description');
	}
}
