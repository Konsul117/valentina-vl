<?php

namespace common\components;

use DateTime;
use Yii;

class Formatter extends \yii\i18n\Formatter {

	protected $localTimezone;

	public function init() {
		parent::init();

		if (isset(Yii::$app->params['localTimezoneOffset'])) {
			$offset = Yii::$app->params['localTimezoneOffset'];
		}
		else {
			$offset = 0;
		}

		$this->localTimezone = DateTimeZone::getTimezoneByUtcOffset($offset);

	}

	/**
	 * Вывод даты, времени в локальном формате
	 * @param      $value
	 * @param null $format
	 * @return string
	 */
	public function asLocalDateTime($value, $format = null) {
		if ($format === null) {
			$format = $this->dateFormat;
		}

		if ($value === null) {
			return '';
		}

		return (new DateTime($value, new DateTimeZone('UTC')))
			->setTimezone($this->localTimezone)
			->format($format);
	}

	/**
	 * Вывод даты в локальном формате
	 * @param      $value
	 * @param null $format
	 * @return string
	 */
	public function asLocalDate($value, $format = null) {
		if ($format === null) {
			$format = 'd.m.Y';
		}

		if ($value === null) {
			return '';
		}

		return (new DateTime($value, new DateTimeZone('UTC')))
			->setTimezone($this->localTimezone)
			->format($format);
	}

}