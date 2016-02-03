<?php

namespace frontend\modules\blogFront\components;

use common\modules\image\Image;
use common\modules\image\models\ImageProvider;
use phpQuery;
use Yii;
use yii\base\Exception;

class PostOutHelper {

	/** Параметр data для тега ссылки a для вызова lightbox */
	const LINK_LIGHTBOX_PARAM = 'lightbox';

	/**
	 * Оборачивание изображений контента для кликабельности
	 *
	 * @param string $content      Контент поста
	 * @param array  $checkFormats Форматы изображения, для которых нужно проверить и регенерировать тамбы
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public static function wrapContentImages($content, $checkFormats = []) {
		/** @var Image $imageModule */
		$imageModule = Yii::$app->getModule('image');

		//парсим id изображений в теле поста
		$doc = phpQuery::newDocumentHTML($content);

		//проходися по тегам всех изображений
		foreach ($doc->find('img[data-image-id]') as $imgEl) {
			//получаем id изображения
			$imageId = (int) $imgEl->getAttribute('data-image-id');
			if (!$imageId) {
				//если id изображения нет, то пропускаем его
				continue;
			}

			if (!$imageModule->imageThumbCreator->checkOriginImageExists($imageId)) {
				//если оригинальный файл отсутствует, то пропускаем изображение
				continue;
			}

			//генерируем новые элементы a и img
			$a = $imgEl->ownerDocument->createElement('a');

			$a->setAttribute('href', $imageModule->imageThumbCreator->getImageThumbUrl($imageId, ImageProvider::FORMAT_FULL));
			$a->setAttribute('data-' . static::LINK_LIGHTBOX_PARAM, '1');
			$a->setAttribute('rel', 'img_group');

			$img = $imgEl->ownerDocument->createElement('img');
			$img->setAttribute('src', $imgEl->getAttribute('src'));
			$a->appendChild($img);

			//и заменяем элементы

			$imgEl->parentNode->replaceChild($a, $imgEl);

			if (!empty($checkFormats)) {
				//вызываем получение среднего тамба для проверки наличия

				foreach ($checkFormats as $format) {
					$imageModule->imageThumbCreator->touchThumb($imageId, $format);
				}
			}
		}

		return (string) $doc;
	}

	/**
	 * Очистка строки от тегов, символов переноса
	 *
	 * @param string $str Строка
	 *
	 * @return string
	 */
	public static function clearString($str) {
		$str = strip_tags($str);
		$str = str_replace("\n", '', $str);
		$str = str_replace("\r", '', $str);

		return $str;
	}

}