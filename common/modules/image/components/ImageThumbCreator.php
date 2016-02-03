<?php

namespace common\modules\image\components;;

use common\exceptions\ImageException;
use common\modules\config\Config;
use PHPImageWorkshop\Exception\ImageWorkshopBaseException;
use PHPImageWorkshop\ImageWorkshop;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\helpers\Url;

/**
 * Компонент работы с тамбами
 */
class ImageThumbCreator extends Component {

	/**
	 * Размеры для тамбов
	 *
	 * @var array
	 */
	public $thumbsSizes;

	/**
	 * Получить url для тамба изображения
	 *
	 * @param string $imageIdent идентификатор изображения
	 * @param string $format     формат изображения @see ImageProvider
	 * @return string URL изображения
	 * @throws ImageException
	 */
	public function getImageThumbUrl($imageIdent, $format) {
		$this->touchThumb($imageIdent, $format);

		try {
			$url = Yii::$app->params['frontendDomain'] . '/';

			if (Yii::$app->request->baseUrl) {
				$url .= Yii::$app->request->baseUrl . '/';
			}

			$resizedFilename = $this->getResizedFilename($imageIdent, $format);

			$url .= Yii::getAlias('@resized_images_url') . '/' . $resizedFilename;

			return 'http://' . Url::to($url);
		}
		catch (InvalidParamException $e) {
			throw new ImageException('Исключение при получении URL изображения: ' . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * Проверка и генерация тамба при его отсутствии
	 *
	 * @param string $imageIdent идентификатор изображения
	 * @param string $format     формат изображения @see ImageProvider
	 * @param bool $imageIdent   Игнорировать случай, когда оригинал изображения отсутствует
	 *
	 * @throws Exception
	 * @throws ImageException
	 */
	public function touchThumb($imageIdent, $format, $ignoreOnAbsent = false) {
		$resizedFilename = $this->getResizedFilename($imageIdent, $format);
		try {
			$resizedFilePath = $this->getResizedPath() . DIRECTORY_SEPARATOR . $resizedFilename;
		}
		catch (Exception $e) {
			throw new ImageException('Исключение при получении пути сохранения изображений: ' . $e->getMessage(), 0,
				$e);
		}

		if (!file_exists($resizedFilePath)) {
			try {
				$originalFilePath = Yii::getAlias('@upload_images_path') . DIRECTORY_SEPARATOR . $imageIdent . '.jpg';
			}
			catch (InvalidParamException $e) {
				throw new ImageException('Исключение при получении пути изображения: ' . $e->getMessage(), 0, $e);
			}

			if (!file_exists($originalFilePath)) {
				if (!$ignoreOnAbsent) {
					return;
				}
				else {
					throw new ImageException('Изображение отсутствует');
				}
			}

			if (!isset($this->thumbsSizes[$format])) {
				throw new ImageException('Указан некорректный формат тамба: ' . $format);
			}

			$sizeParams = $this->thumbsSizes[$format];

			if (!isset($sizeParams['width']) || !isset($sizeParams['height'])) {
				throw new ImageException('Некорректная конфигурация формата тамба: ' . $format);
			}

			try {
				$imageLayer = ImageWorkshop::initFromPath($originalFilePath);

				if (isset($sizeParams['crop']) && $sizeParams['crop']) {

					$imageLayer->resizeByNarrowSideInPixel($sizeParams['width'], true);
					$imageLayer->cropMaximumInPixel(0, 0, 'mm');
				}
				else {
					$imageLayer->resizeToFit($sizeParams['width'], $sizeParams['height'], true);
				}

				if (isset($sizeParams['watermark']) && $sizeParams['watermark']) {

					$watermarkPath = $this->getWatermarkPath();

					if ($watermarkPath !== null) {
						$watermark = ImageWorkshop::initFromPath($watermarkPath);
						$imageLayer->addLayerOnTop($watermark, 0, 0, 'RB');
					}
				}

				$imageLayer->save($this->getResizedPath(), $resizedFilename);
			}
			catch (ImageWorkshopBaseException $e) {
				throw new ImageException('Исключение при генерации тамба: ' . $e->getMessage(), 0, $e);
			}

			if (!file_exists($resizedFilePath)) {
				throw new ImageException('Созданный тамб не сохранился. Путь: ' . $resizedFilePath);
			}
		}
	}

	public function getResizedFilename($imageIdent, $format) {
		return $imageIdent . '_' . $format . '.jpg';
	}

	/**
	 * @return bool|string
	 * @throws Exception
	 */
	protected function getResizedPath() {
		try {
			$path = Yii::getAlias('@resized_images_path');
		}
		catch (InvalidParamException $e) {
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
	 * Получить путь к файлу вотермарка
	 *
	 * @return null|string
	 */
	protected function getWatermarkPath() {
		/** @var Config $configModule */
		$configModule  = Yii::$app->getModule('config');
		$watermarkPath = $configModule->getParamValue('watermarkPath');

		if (($watermarkPath !== null) && !file_exists($watermarkPath)) {
			$watermarkPath = null;
		}

		return $watermarkPath;
	}

	/**
	 * Вычищение всех созданных тамбов для изображения
	 *
	 * @param string $imageIdent идентификатор изображения
	 * @throws Exception
	 * @throws ImageException
	 */
	public function clearThumbs($imageIdent) {
		foreach (glob($this->getResizedPath() . DIRECTORY_SEPARATOR . $imageIdent . '_*') as $path) {
			if (is_writable($path)) {
				unlink($path);
			}
			else {
				throw new ImageException('Не удалось удалить тамб фото - нет доступа: ' . $path);
			}
		}
	}
}