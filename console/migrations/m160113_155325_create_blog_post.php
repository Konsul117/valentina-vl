<?php

use yii\db\Schema;
use yii\db\Migration;

class m160113_155325_create_blog_post extends Migration {

	var $tableName = 'blog_post';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'id'           => $this->primaryKey() . ' COMMENT "Уникальный идентификатор поста"',
			'category_id'  => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Категория"',
			'title'        => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "Заголовок поста"',
			'title_url'    => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "Заголовок ЧПУ поста"',
			'content'      => Schema::TYPE_TEXT . ' NOT NULL DEFAULT "" COMMENT "Контент"',
			'tags'         => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT "" COMMENT "Теги"',
			'is_published' => 'TINYINT NOT NULL DEFAULT 0 COMMENT "Состояние опубликованности"',
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Дата-время создания поста"',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Дата-время обновления поста"',
		], ' COMMENT "Посты блога"');

		$this->createIndex('ix-' . $this->tableName . '-[category_id]', $this->tableName, ['category_id']);
		$this->createIndex('ix-' . $this->tableName . '-[title_url]', $this->tableName, ['title_url']);
		$this->createIndex('ix-' . $this->tableName . '-[insert_stamp]', $this->tableName, ['insert_stamp']);
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
