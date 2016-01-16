<?php

namespace common\interfaces;

/**
 * Интерфейс доступа к изображению, получению различных форматов и пр.
 */
interface ImageProvider {

	/** Формат превью */
	const FORMAT_THUMB = 'thumb';

	/** Формат среднего разера */
	const FORMAT_MEDIUM = 'medium';

	/** Формат полный */
	const FORMAT_FULL = 'full';

	/**
	 * Получить изображение нужного формата
	 * @param string $format
	 * @return string URL изображения
	 */
	public function getImageUrl($format);
}