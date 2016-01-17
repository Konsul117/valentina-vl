<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_091258_create_blog_post_tag extends Migration {

	var $tableName = 'blog_post_tag';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'post_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Id поста"',
			'tag_id'  => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Id тега"',
			'sort'    => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 COMMENT "Индекс сортировки"',
		], ' COMMENT "Связь постов блога и тегов"');

		$this->createIndex('ux-' . $this->tableName . '-[post_id,tag_id]', $this->tableName, ['post_id', 'tag_id'], true);
		$this->createIndex('ix-' . $this->tableName . '-[post_id]', $this->tableName, ['post_id']);
		$this->createIndex('ix-' . $this->tableName . '-[sort]', $this->tableName, ['sort']);
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
