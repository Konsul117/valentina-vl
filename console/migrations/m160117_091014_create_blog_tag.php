<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_091014_create_blog_tag extends Migration {

	var $tableName = 'blog_tag';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'id'           => $this->primaryKey() . ' COMMENT "Уникальный идентификатр тега"',
			'name'         => Schema::TYPE_STRING . '(50) NOT NULL COMMENT "Тег"',
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Дата-время создания тега"',
		], ' COMMENT "Теги"');
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
