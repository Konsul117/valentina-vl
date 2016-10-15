<?php

namespace common\modules\image\models;

/**
 * Интерфейс доступа к изображению, получению различных форматов и пр.
 */
interface ImageProvider {

	/** Формат мини-превью на главной странице */
	const FORMAT_THUMB_FRONT_MAIN = 'thumb-front-main';

	/** Формат превью */
	const FORMAT_THUMB = 'thumb';

	/** Формат изображений постов для главной страницы */
	const FORMAT_POST_MAIN = 'post-main';

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

	/**
	 * @return int
	 */
	public function getIdent();
}