<?php

namespace common\interfaces;

/**
 * Интерфейс сущности
 */
interface EntityInterface {

	/**
	 * Получить URL сущности для фронтэнда
	 *
	 * @return string
	 */
	public function getFrontItemUrl();

	/**
	 * Получить название модели
	 *
	 * @return string
	 */
	public function getEntityTitle();

	/**
	 * Получить название сущности модели
	 *
	 * @return string
	 */
	public function getEntityItemTitle();

}