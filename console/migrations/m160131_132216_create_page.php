<?php

use yii\db\Schema;
use yii\db\Migration;

class m160131_132216_create_page extends Migration {

	var $tableName = 'page';

	var $mainPageContent = '<p>Мой дом - город у моря.</p>
<p>Моя жизнь - мое творчество.</p>

<p>По профессии я лодырь:) С фотоаппаратом наперевес. Снимала много кого, чего и когда. Люблю эксперименты. Провожу выездные фотосесии, предметные, немного репортажки, очень люблю портреты. Ничего не имею против работы вторым фотографом на мероприятиях. Нахожусь в постоянном поиске моделей.</p>

<p>
	Бисером прониклась давно, сначала мастерила ерунду, позже начала делать украшения для съемок. Ныне бисер - моя панацея. Практически любое из моих изделий поддается корректировке, дрессировке и повтору. <br />
Я принимаю участие в городских и краевых ярмарках, выставках (информацию о которых можно узнать <a href="http://www.prim-mastera.com" "target"="_blank">тут</a>, выполняю украшения на заказ. Состою в клубе рукоделий "Славяночка".
</p>

<p>Кроме того, немного пишу, держу дома удава и обожаю готовить.<br />
В моей жизни всегда есть место новым увлечениям, проектам и знакомствам.</p>

<p>
***<br />
-Моня, ты счастлив?<br />
-А шо делать?...<br />
***
</p>
<p>
Не без придури:)
</p>';

	public function safeUp() {
		$this->createTable($this->tableName, [
			'id'           => $this->primaryKey() . ' COMMENT "Уникальный идентификатор"',
			'title'        => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "Название страницы"',
			'title_url'    => Schema::TYPE_STRING . '(255) NOT NULL COMMENT "Название ЧПУ страницы"',
			'content'      => Schema::TYPE_TEXT . ' NOT NULL DEFAULT "" COMMENT "Контент страницы"',
			'is_published' => 'TINYINT NOT NULL DEFAULT 0 COMMENT "Состояние опубликованности"',
			'insert_stamp' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Дата-время создания поста"',
			'update_stamp' => Schema::TYPE_DATETIME . ' NOT NULL COMMENT "Дата-время обновления поста"',
		], 'COMMENT "Страницы"');

		$this->createIndex('ix-' . $this->tableName . '-[title_url]', $this->tableName, ['title_url']);
		$this->createIndex('ix-' . $this->tableName . '-[insert_stamp]', $this->tableName, ['insert_stamp']);

		$currentDateTime = new DateTime('now', new DateTimeZone('UTC'));

		$this->batchInsert(
			$this->tableName,
			['id', 'title', 'title_url', 'content', 'is_published', 'insert_stamp', 'update_stamp'],
			[
				[1, 'Главная страница', 'main_page', $this->mainPageContent, 1, $currentDateTime->format('Y-m-d H:i:s'), $currentDateTime->format('Y-m-d H:i:s')],
				[2, 'Контакты', 'contacts', '', 1, $currentDateTime->format('Y-m-d H:i:s'), $currentDateTime->format('Y-m-d H:i:s')],
			]
		);
	}

	public function safeDown() {
		$this->dropTable($this->tableName);
	}
}
