<?php

namespace backend\modules\editor\components;

use common\components\UploadedFileParams;
use common\exceptions\ImageException;
use PHPImageWorkshop\ImageWorkshop;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class ImageUploader extends Component {

	/**
	 * Максимальная ширина для оригинала
	 * @var int
	 */
	public $maxOriginalWidth;

	/**
	 * Максимальная высота для оригинала
	 * @var int
	 */
	public $maxOriginalHeight;

	/**
	 * Загрузка изображения
	 * @param string $imageIdent идентификатор изображения
	 * @param UploadedFileParams $uploadParams
	 * @throws ImageException
	 */
	public function uploadImage($imageIdent, UploadedFileParams $uploadParams) {

		if ($this->maxOriginalHeight === null || $this->maxOriginalWidth === null) {
			throw new ImageException('Для компонента ImageUploader не настроены параметры макс. ширины и высоты оригинала изображения');
		}

		if ($uploadParams->error !== UPLOAD_ERR_OK) {
			$error = 'Ошибка загрузки изображения: ';

			switch($uploadParams->error) {
				case UPLOAD_ERR_INI_SIZE:
					$error = 'размер превысил лимит ' . ini_get('upload_max_filesize');
					break;
			}
			throw new ImageException($error);
		}

		$imageLayer = ImageWorkshop::initFromPath($uploadParams->tmpName);

		if ($imageLayer->getHeight() > $this->maxOriginalHeight || $imageLayer->getWidth() > $this->maxOriginalWidth) {
			$imageLayer->resizeToFit($this->maxOriginalWidth, $this->maxOriginalHeight, true);
		}

		$savePath = $this->getSavePath();
		$filename = $imageIdent . '.jpg';

		$imageLayer->save($savePath, $filename, true, null, 100);

		//т.к. ImageResize::save() не возвращает результат сохранения, то приходиться проверять результат по наличию итогового файла
		if (!file_exists($savePath . DIRECTORY_SEPARATOR . $filename)) {
			throw new ImageException('Ошибка при сохранении изображения: файл не создан');
		}

	}

	protected function getSavePath() {
		$path = Yii::getAlias('@upload_images_path');

		if (!file_exists($path)) {
			if (!@mkdir($path)) {
				throw new Exception('Ошибка при создании каталона для загруженных изображений: ' . $path);
			}
		}

		return $path;
	}


}