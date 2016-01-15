<?php

namespace backend\modules\editor\controllers;

use common\components\AjaxResponse;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Контроллер загрузки изображений
 */
class UploadController extends Controller {

	/**
	 * @var AjaxResponse
	 */
	protected $ajaxResponse;

	public $enableCsrfValidation = false;

	public function init() {
		parent::init();
		$this->ajaxResponse = new AjaxResponse();
	}

	public function afterAction($action, $result) {
		parent::afterAction($action, $result);
		if (Yii::$app->response->format == Response::FORMAT_JSON) {
			header('Content-Type: application/json');

			return $this->ajaxResponse;
		}

		return null;
	}

	/**
	 * Загрузка изображения
	 */
	public function actionUploadImage() {
		Yii::$app->response->format = Response::FORMAT_JSON;
		if (isset($_FILES['file'])) {
			$this->ajaxResponse->data['files'][] = $_FILES['file'];
		}
	}

}