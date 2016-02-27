<?php

use yii\db\Schema;
use yii\db\Migration;

class m160227_133223_alter_entity extends Migration {

	var $tableName = 'entity';

	public function safeUp() {
		$this->addColumn($this->tableName, 'entity_class', Schema::TYPE_STRING . '(255) COMMENT "Класс реализации модели сущности" AFTER `title`');

		$this->update(
			$this->tableName,
			['entity_class' => \common\modules\blog\models\BlogPost::class],
			['id' => \common\models\Entity::ENTITY_BLOG_POST_ID]
		);

		$this->update(
			$this->tableName,
			['entity_class' => \common\modules\page\models\Page::class],
			['id' => \common\models\Entity::ENTITY_PAGE_ID]
		);
	}

	public function safeDown() {
		$this->dropColumn($this->tableName, 'entity_class');
	}
}
