<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_135732_create_config_param extends Migration {

	var $tableName = 'config_param';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'name' => Schema::TYPE_STRING . '(255) COMMENT "Имя параметра"',
			'value' => Schema::TYPE_STRING . '(255) COMMENT "Значение параметра"',
		], 'COMMENT "Конфигурация параметров"');

		$this->addPrimaryKey('pk-' . $this->tableName . '-[name]', $this->tableName, ['name']);
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
