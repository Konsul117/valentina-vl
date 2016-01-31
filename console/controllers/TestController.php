<?php

namespace console\controllers;

use common\models\Image;
use yii\console\Controller;
use yii\helpers\HtmlPurifier;

/**
 * Тесты
 */
class TestController extends Controller {

	public function actionIndex() {
		echo HtmlPurifier::process('<h1>qwe</h1>');
	}

	public function actionStr() {
		$strs = explode(' ', 'qwe  asd   tret ');
		var_dump($strs);
		$strs = array_filter($strs, function($item) {
			return $item !== '';
		});

		var_dump($strs);
	}

	public function actionImgClear() {
		/** @var Image $image */
		$image = Image::findOne(62);

		$image->delete();
	}
}