<?php

namespace common\components;

use yii\base\InvalidParamException;

/**
 * Обёртка для параметров загруженного файла (из $_FILES)
 */
class UploadedFileParams {

	/**
	 * Имя загруженного файла
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Тип данных файла
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Имя временного файла, в котором хранится содержимое загруженного файла
	 *
	 * @var string
	 */
	public $tmpName;

	/**
	 * Идентификатор ошибки загрузки
	 *
	 * @var int
	 */
	public $error;

	/**
	 * Размер (в байтах)
	 *
	 * @var int
	 */
	public $size;

	/**
	 * Получить по массиву объект параметров
	 *
	 * @param array $array
	 *
	 * @return static
	 * @throws InvalidParamException
	 */
	public static function getInstanceByArray(array $array) {
		$obj = new static();

		if (!isset($array['name'])) {
			throw new InvalidParamException('Отсутствует параметр "name"');
		}

		if (!isset($array['type'])) {
			throw new InvalidParamException('Отсутствует параметр "type"');
		}

		if (!isset($array['tmp_name'])) {
			throw new InvalidParamException('Отсутствует tmp_name"');
		}

		if (!isset($array['error'])) {
			throw new InvalidParamException('Отсутствует параметр "error"');
		}

		if (!isset($array['size'])) {
			throw new InvalidParamException('Отсутствует параметр "size"');
		}

		$obj->name    = $array['name'];
		$obj->type    = $array['type'];
		$obj->tmpName = $array['tmp_name'];
		$obj->error   = $array['error'];
		$obj->size    = $array['size'];

		return $obj;
	}

}