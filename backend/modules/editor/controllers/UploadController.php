<?php

namespace backend\modules\editor\controllers;

use backend\modules\editor\components\ImageUploader;
use common\components\AjaxResponse;
use common\components\UploadedFileParams;
use common\exceptions\ImageException;
use common\interfaces\ImageProvider;
use common\models\Image;
use Yii;
use yii\base\InvalidParamException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Контроллер загрузки изображений
 */
class UploadController extends Controller {

	public $enableCsrfValidation = false;
	/**
	 * @var AjaxResponse
	 */
	protected $ajaxResponse;

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

		if (!isset($_FILES['file'])) {
			$this->ajaxResponse->success = false;
			$this->ajaxResponse->error   = 'Файл не получен';

			return;
		}

		try {
			$param = UploadedFileParams::getInstanceByArray($_FILES['file']);
		}
		catch (InvalidParamException $e) {
			$this->ajaxResponse->success = false;
			$this->ajaxResponse->error   = $e->getMessage();

			return;
		}

		$transaction = Yii::$app->db->beginTransaction();
		$image = new Image();

		$relatedEntityItemId = Yii::$app->request->post('related_entity_item_id');

		if ($relatedEntityItemId !== null) {
			$image->related_entity_item_id = $relatedEntityItemId;
		}

		if ($image->save() === false) {
			$this->ajaxResponse->success = false;
			$this->ajaxResponse->error   = 'Ошибка сохранения изображения';

			return;
		}

		/** @var ImageUploader $imageUploader */
		$imageUploader = Yii::$app->modules['editor']->imageUploader;

		if ($imageUploader === null) {
			$this->ajaxResponse->success = false;
			$this->ajaxResponse->error   = 'Системная ошибка';
		}

		try {

			$imageUploader->uploadImage((string)$image->id, $param);

			$transaction->commit();

			$this->ajaxResponse->success = true;

			$this->ajaxResponse->data = [
				'urls'     => [
					'medium' => $image->getImageUrl(ImageProvider::FORMAT_MEDIUM),
					'thumb'  => $image->getImageUrl(ImageProvider::FORMAT_THUMB),
				],
				'image_id' => $image->id,
			];
		}
		catch (ImageException $e) {
			$this->ajaxResponse->success = false;
			$this->ajaxResponse->error   = $e->getMessage();

			$transaction->rollBack();

			return;
		}
	}

}