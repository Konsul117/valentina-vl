<?php

namespace common\components;

class AjaxResponse {

	/**
	 * Данные
	 * @var array
	 */
	public $data;

	/**
	 * Успешность запроса
	 * @var boolean
	 */
	public $success;

	/**
	 * Ошибки
	 * @var string
	 */
	public $error;

	/**
	 * Html-контент
	 * @var string
	 */
	public $html;


}