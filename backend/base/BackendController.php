<?php

namespace backend\base;

use backend\modules\user\controllers\AuthController;
use Yii;
use yii\web\Controller;

class BackendController extends Controller {

	public function beforeAction($action) {
		if (parent::beforeAction($action) === false) {
			return false;
		}

		if (Yii::$app->user->isGuest && get_called_class() !== AuthController::class) {
			return Yii::$app->user->loginRequired();
		}

		return true;
	}

}