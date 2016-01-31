<?php

namespace frontend\modules\commentFront\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * ПОка не используется!
 * Контроллер добавления комментариев
 */
class AddCommentController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class'   => VerbFilter::class,
				'actions' => [
					'add' => ['post'],
				],
			],
		];
	}

	/**
	 * Добавление комментария
	 */
	public function actionAdd() {

	}

}