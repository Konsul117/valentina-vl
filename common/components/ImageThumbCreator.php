<?php

namespace common\components;

use common\exceptions\ImageException;
use Eventviva\ImageResize;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\helpers\Url;

class ImageThumbCreator extends Component {

	/**
	 * Размеры для тамбов
	 * @var array
	 */
	public $thumbsSizes;

	/**
	 * Получить url для тамба изображения
	 * @param string $imageIdent идентификатор изображения
	 * @param string $format формат изображения @see ImageProvider
	 * @return string URL изображения
	 * @throws ImageException
	 */
	public function getImageThumbUrl($imageIdent, $format) {
		$resizedFilename = $imageIdent . '_' . $format . '.jpg';
		try {
			$resizedFilePath = $this->getResizedPath() . DIRECTORY_SEPARATOR . $resizedFilename;
		}
		catch (Exception $e) {
			throw new ImageException('Исключение при получении пути сохранения изображений: ' . $e->getMessage(), 0, $e);
		}

		if (!file_exists($resizedFilePath)) {
			try {
				$originalFilePath = Yii::getAlias('@upload_images_path') . DIRECTORY_SEPARATOR . $imageIdent . '.jpg';

			}
			catch (InvalidParamException $e) {
				throw new ImageException('Исключение при получении пути изображения: ' . $e->getMessage(), 0, $e);
			}

			if (!file_exists($originalFilePath)) {
				throw new ImageException('Изображение отсутствует');
			}

			try {
				$imageResize = new ImageResize($originalFilePath);

			}
			catch (\Exception $e) {
				throw new ImageException('Исключение при обработке изображения: ' . $e->getMessage(), 0, $e);
			}

			if (!isset($this->thumbsSizes[$format])) {
				throw new ImageException('Указан некорректный формат тамба: ' . $format);
			}

			$sizeParams = $this->thumbsSizes[$format];

			if (!isset($sizeParams['width']) || !isset($sizeParams['height'])) {
				throw new ImageException('Некорректная конфигурация формата тамба: ' . $format);
			}

			$imageResize->crop($sizeParams['width'], $sizeParams['height']);
			$imageResize->save($resizedFilePath);

			if (!file_exists($resizedFilePath)) {
				throw new ImageException('Созданный тамб не сохранился. Путь: ' . $resizedFilePath);
			}
		}

		try {
			$url = Yii::$app->params['frontendDomain'] . '/';

			if (Yii::$app->request->baseUrl) {
				$url .=  Yii::$app->request->baseUrl . '/';
			}

			$url .= Yii::getAlias('@resized_images_url') . '/' . $resizedFilename;

			return 'http://' . Url::to($url);
		}
		catch(InvalidParamException $e) {
			throw new ImageException('Исключение при получении URL изображения: ' . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * @return bool|string
	 * @throws Exception
	 */
	protected function getResizedPath() {
		try {
			$path = Yii::getAlias('@resized_images_path');
		}
		catch(InvalidParamException $e) {
			throw new ImageException('Исключение при получении URL изображения: ' . $e->getMessage(), 0, $e);
		}

		if (!file_exists($path)) {
			if (!@mkdir($path, 0777, true)) {
				throw new Exception('Ошибка при создании каталона для загруженных изображений: ' . $path);
			}
		}

		return $path;
	}

	/**
	 * Вычищение всех созданных тамбов для изображения
	 * @param string $imageIdent идентификатор изображения
	 * @throws Exception
	 * @throws ImageException
	 */
	public function clearThumbs($imageIdent) {
		foreach(glob($this->getResizedPath() . DIRECTORY_SEPARATOR . $imageIdent . '_*') as $path) {
			if (is_writable($path)) {
				unlink($path);
			}
			else {
				throw new ImageException('Не удалось удалить тамб фото - нет доступа: ' . $path);
			}
		}
	}
}