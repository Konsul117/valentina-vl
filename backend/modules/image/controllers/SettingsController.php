<?php

namespace backend\modules\image\controllers;

use yii\base\Exception;
use yii\web\Controller;
use common\components\UploadedFileParams;
use common\modules\config\Config;
use Yii;
use yii\base\InvalidParamException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Контроллер настроек изображений
 */
class SettingsController extends Controller {

	const CONFIG_PATH = 'watermarkPath';

	/**
	 * Главная страница
	 *
	 * @return string
	 */
	public function actionIndex() {
		return $this->render('index', []);
	}

	/**
	 * Страница загрузки вотермарка
	 *
	 * @return string
	 *
	 * @throws ServerErrorHttpException
	 */
	public function actionWatermark() {
		/** @var Config $configModule */
		$configModule = Yii::$app->getModule('config');

		if (Yii::$app->request->isPost) {

			try {
				$param = UploadedFileParams::getInstanceByArray($_FILES['watermark']);

				$addResult = $this->uploadWatermark($param);
			}
			catch (InvalidParamException $e) {
				Yii::$app->session->addFlash('error', 'Ошибка при загрузке водяного знака: ' . $e->getMessage());
				throw new ServerErrorHttpException($e->getMessage(), 0, $e);
			}

			if ($addResult) {
				Yii::$app->session->addFlash('success', 'Новый водяной знак был успешно загружен');

				$configModule->setParamValue(static::CONFIG_PATH, $addResult);
			}
			else {
				Yii::$app->session->addFlash('error', 'Неизвестная ошибка при загрузке водяного знака');
			}

			return $this->refresh();
		}

		$watermarkPath = $configModule->getParamValue(static::CONFIG_PATH);

		if (!file_exists($watermarkPath)) {
			$watermarkPath = null;
		}

		return $this->render('watermark', [
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

	public function actionWatermarkShow() {
		/** @var Config $configModule */
		$configModule  = Yii::$app->getModule('config');
		$watermarkPath = $configModule->getParamValue(static::CONFIG_PATH);

		if (!file_exists($watermarkPath)) {
			throw new InvalidParamException('Водяной знак не существует');
		}

		Yii::$app->response->format = Response::FORMAT_RAW;
		Yii::$app->response->headers->set('Content-type', 'image/png');

		return file_get_contents($watermarkPath);
	}

	public function actionClearThumbs() {

		if (Yii::$app->request->isPost && (Yii::$app->request->post('clear'))) {
			try {
				$result = $this->clearThumbs();
			}
			catch (Exception $e) {
				$result = false;
				Yii::$app->session->addFlash('error', 'Ошибка: ' . $e->getMessage());
			}
			if ($result !== false) {
				Yii::$app->session->addFlash('success', 'Изображения успешно очищены. Удалено изображений: ' . $result);
			}
			else {
				Yii::$app->session->addFlash('error', 'Ошибка при очистке изображений');
			}
		}

		return $this->render('clear-thumbs');
	}

	/**
	 * Очистка тамбов (удаление всех)
	 *
	 * @return int|false количество удалённых файлов, если false - то ошибка
	 *
	 * @throws Exception
	 */
	protected function clearThumbs() {
		try {
			$path = Yii::getAlias('@resized_images_path');
		}
		catch (InvalidParamException $e) {
			throw new Exception('Ошибка при получении каталога тамбов: ' . $e->getMessage(), 0, $e);
		}

		if (!file_exists($path)) {
			return 0;
		}

		$count = 0;

		foreach (glob($path . DIRECTORY_SEPARATOR . '*.jpg') as $filename) {
			$delResult = @unlink($filename);

			if (!$delResult) {
				throw new Exception('Не удалось удалить файл: ' . $filename);
			}
			else {
				$count++;
			}
		}

		return $count;
	}
}