<?php

use yii\db\Schema;
use yii\db\Migration;

class m160113_155323_create_blog_category extends Migration {

	var $tableName = 'blog_category';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'id'        => $this->primaryKey() . ' COMMENT "Уникальный идентификатор категории"',
			'title'     => Schema::TYPE_STRING . '(100) NOT NULL COMMENT "Название категории"',
			'title_url' => Schema::TYPE_STRING . '(100) NOT NULL COMMENT "Название ЧПУ категории"',
		], ' COMMENT "Категории блога"');

		$this->batchInsert($this->tableName, ['id', 'title', 'title_url'], [
			[1, 'Бисер', 'biser'],
			[2, 'Не бисер', 'not_biser'],
		]);
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
