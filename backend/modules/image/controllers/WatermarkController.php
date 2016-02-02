<?php

namespace backend\modules\image\controllers;

use common\components\UploadedFileParams;
use common\modules\config\Config;
use Yii;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

/**
 * Контроллер для работы с вотермарками
 */
class WatermarkController extends Controller {

	const CONFIG_PATH = 'watermarkPath';

	/**
	 * Главная страница
	 *
	 * @return string
	 *
	 * @throws ServerErrorHttpException
	 */
	public function actionIndex() {
		/** @var Config $configModule */
		$configModule = Yii::$app->getModule('config');

		if (Yii::$app->request->isPost) {

			try {
				$param = UploadedFileParams::getInstanceByArray($_FILES['watermark']);

				$addResult = $this->uploadWatermark($param);
			}
			catch (InvalidParamException $e) {
				Yii::$app->session->addFlash('success', 'Ошибка при загрузке водяного знака: ' . $e->getMessage());
				throw new ServerErrorHttpException($e->getMessage(), 0, $e);
			}

			if ($addResult) {
				Yii::$app->session->addFlash('success', 'Новый водяной знак был успешно загружен');

				$configModule->setParamValue(static::CONFIG_PATH, $addResult);
			}
			else {
				Yii::$app->session->addFlash('success', 'Неизвестная ошибка при загрузке водяного знака');
			}

			return $this->refresh();
		}

		$watermarkPath = $configModule->getParamValue(static::CONFIG_PATH);

		if (!file_exists($watermarkPath)) {
			$watermarkPath = null;
		}

		return $this->render('index', [
			'watermarkExists' => ($watermarkPath !== null),
		]);
	}

	/**
	 * Загрузка водяного знака
	 *
	 * @param UploadedFileParams $fileParams
	 *
	 * @return string|false Путь к загруженному файлу или false в случае ошибки
	 *
	 * @throws InvalidParamException
	 */
	protected function uploadWatermark(UploadedFileParams $fileParams) {
		if ($fileParams->error !== 0) {
			throw new InvalidParamException('Ошибка при загрузке файла');
		}

		if (!preg_match('/\.png$/i', $fileParams->name)) {
			throw new InvalidParamException('Загружаемый файл должен быть формата PNG');
		}

		$watermarkPath = Yii::getAlias('@upload_watermark_path');

		if (!file_exists($watermarkPath)) {
			if (!@mkdir($watermarkPath, 0777, true)) {
				throw new InvalidParamException('Ошибка сервера: невозможно создать каталог ' . $watermarkPath);
			}
		}

		$filepath = $watermarkPath . DIRECTORY_SEPARATOR . 'watermark.png';

		if (move_uploaded_file($fileParams->tmpName, $filepath)) {
			return $filepath;
		}

		return false;
	}

	public function actionWatermark() {
		/** @var Config $configModule */
		$configModule  = Yii::$app->getModule('config');
		$watermarkPath = $configModule->getParamValue(static::CONFIG_PATH);

		if (!file_exists($watermarkPath)) {
			throw new InvalidParamException('Водяной знак не существует');
		}

		Yii::$app->response->headers->set('Content-Type', 'image/png');

		return file_get_contents($watermarkPath);
	}

}