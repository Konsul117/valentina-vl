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

	//теги
	/** Тэг title */
	const TAG_TITLE = 'title';
	/** Тег alt */
	const TAG_ALT   = 'alt';

	/**
	 * Оборачивание изображений контента для кликабельности
	 *
	 * @param string $content      Контент поста
	 * @param string  $srcFormat Формат изображения, для которых нужно проверить и регенерировать тамбы
	 * @param string $defaultTitle Название изображений по-умолчанию (если в самом изображении оно не указано,
	 *                             то задаётся переданное в данном параметре)
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public static function wrapContentImages($content, $srcFormat, $defaultTitle = null) {
		/** @var Image $imageModule */
		$imageModule = Yii::$app->getModule('image');

		//парсим id изображений в теле поста
		$doc = phpQuery::newDocumentHTML($content);

		//проходися по тегам всех изображений
		foreach ($doc->find('img[data-image-id]') as $imgEl) {
			/** @var \DOMElement $imgEl */
			//получаем id изображения
			$imageId = (int) $imgEl->getAttribute('data-image-id');

			if (!$imageId) {
				//если id изображения нет, то пропускаем его
				continue;
			}

			/** @var \common\modules\image\models\Image $image */
			$image = \common\modules\image\models\Image::getCachedInstance($imageId);

			if ($image === null) {
				//если модель изображения не получилось загрузить, то пропускаем его
				continue;
			}

			if (!$imageModule->imageThumbCreator->checkOriginImageExists($imageId)) {
				//если оригинальный файл отсутствует, то пропускаем изображение
				continue;
			}

			//название изображения
			$imageTitle = null;

			if ($image->title) {
				$imageTitle = $image->title;
			}
			else if ($defaultTitle) {
				$imageTitle = $defaultTitle;
			}

			if ($imageTitle) {
				$imageTitle = static::clearString($imageTitle);
			}

			//генерируем новые элементы a и img
			$a = $imgEl->ownerDocument->createElement('a');

			$a->setAttribute('href', $imageModule->imageThumbCreator->getImageThumbUrl(
				$imageId,
				ImageProvider::FORMAT_FULL,
				$image->is_need_watermark
			));

			if ($imageTitle) {
				$a->setAttribute(static::TAG_TITLE, $imageTitle);
			}

			$a->setAttribute('data-' . static::LINK_LIGHTBOX_PARAM, '1');
			$a->setAttribute('rel', 'img_group');

			$img = $imgEl->ownerDocument->createElement('img');

			foreach($imgEl->attributes as $attr) {
				switch ($attr->name) {
					case 'src':
						$img->setAttribute((string)$attr->name, $imageModule->imageThumbCreator->getImageThumbUrl(
							$imageId,
							$srcFormat,
							$image->is_need_watermark
						));

						break;

					default:
						$img->setAttribute((string)$attr->name, (string)$attr->value);

						break;
				}

			}

			if ($imageTitle) {
				$img->setAttribute(static::TAG_ALT, $imageTitle);
				$img->setAttribute(static::TAG_TITLE, $imageTitle);
			}

			$a->appendChild($img);

			//и заменяем элементы

			$imgEl->parentNode->replaceChild($a, $imgEl);

			if (!empty($checkFormats)) {
				//вызываем получение среднего тамба для проверки наличия

				foreach ($checkFormats as $format) {
					$imageModule->imageThumbCreator->touchThumb($imageId, $format, $image->is_need_watermark);
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
		$str = preg_replace('/(?:"([^>]*)")(?!>)/', '«$1»', $str);
		$str = addslashes($str);
		$str = strip_tags($str);
		$str = str_replace("\n", '', $str);
		$str = str_replace("\r", '', $str);

		return $str;
	}

}