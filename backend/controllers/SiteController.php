<?php
namespace backend\controllers;

use backend\base\BackendController;
use backend\modules\commentBackend\CommentBackend;
use Yii;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends BackendController {

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex() {
		/** @var CommentBackend $commentModule */
		$commentModule = Yii::$app->modules['commentBackend'];

		return $this->render('index', [
			'lastCommentsWiget' => $commentModule->getLastCommentsWidget(),
		]);
	}

	public function actionLogin() {
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}
		else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}
}
