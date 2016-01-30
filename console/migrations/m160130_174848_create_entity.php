<?php

use yii\db\Schema;
use yii\db\Migration;

class m160130_174848_create_entity extends Migration {

	var $tableName = 'entity';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'id'    => $this->primaryKey() . ' COMMENT "Уникальный идентификатор"',
			'title' => Schema::TYPE_STRING . '(100) COMMENT "Название"',
		], ' COMMENT "Справочник сущностей"');

		$this->batchInsert(
			$this->tableName,
			['id', 'title'],
			[
				[1, 'Пост блога'],
				[2, 'Страница'],
			]
		);
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
