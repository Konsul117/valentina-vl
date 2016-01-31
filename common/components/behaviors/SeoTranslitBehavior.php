<?php

namespace common\components\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * Поведение для автоматического указания ЧПУ
 */
class SeoTranslitBehavior extends AttributeBehavior {

	/**
	 * Аттрибут, из которого нужно сгенерировать ЧПУ
	 * @var string
	 */
	public $attributeFrom;

	/**
	 * Аттрибут, в который нужно записать сгенерированный ЧПУ
	 * @var string
	 */
	public $attributeTo;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		if (empty($this->attributes)) {
			$this->attributes = [
				BaseActiveRecord::EVENT_BEFORE_INSERT => $this->attributeTo,
				BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->attributeTo,
			];
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function getValue($event) {
		return $this->generateTitleUrl($event->sender->{$this->attributeFrom});
	}

	/**
	 * Генерация транслитированной строки
	 * @param string
	 * @return string
	 */
	protected function generateTitleUrl($title) {
		$title = preg_replace('/\[([^\]]+)\]/u', '', $title);
		$title = preg_replace('/\(([^\)]+)\)/u', '', $title);

		$translit = [
			'/'  => '-',
			'\\' => '-',
			' '  => '-',
			'а'  => 'a',
			'б'  => 'b',
			'в'  => 'v',
			'г'  => 'g',
			'д'  => 'd',
			'е'  => 'e',
			'ё'  => 'yo',
			'ж'  => 'zh',
			'з'  => 'z',
			'и'  => 'i',
			'й'  => 'j',
			'к'  => 'k',
			'л'  => 'l',
			'м'  => 'm',
			'н'  => 'n',
			'о'  => 'o',
			'п'  => 'p',
			'р'  => 'r',
			'с'  => 's',
			'т'  => 't',
			'у'  => 'u',
			'ф'  => 'f',
			'х'  => 'x',
			'ц'  => 'c',
			'ч'  => 'ch',
			'ш'  => 'sh',
			'щ'  => 'shh',
			'ы'  => 'y',
			'э'  => 'e',
			'ю'  => 'yu',
			'я'  => 'ya',
			'ь'  => '',
			'ъ'  => '',
			'-'  => '-',
		];

		$title = mb_strtolower($title, 'UTF-8');

		$title = str_replace(array_keys($translit), array_values($translit), $title);

		$title = preg_replace('/[^\-_A-z0-9]/u', '', $title);
		$title = str_replace('-[]', '', $title);
		$title = str_replace('[', '', $title);
		$title = str_replace(']', '', $title);
		$title = trim($title, '-');
		$title = preg_replace('/-+/', '-', $title);

		return $title;
	}

}