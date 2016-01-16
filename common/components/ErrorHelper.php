<?php

namespace common\components;

/**
 * Хелпер работы с ошибками
 */
class ErrorHelper {

	/**
	 * Получение ошибок валидации модели в строковом представлении
	 * @param array $errors
	 * @return string
	 */
	public static function getErrorsString(array $errors) {
		return print_r($errors, true);
	}

}