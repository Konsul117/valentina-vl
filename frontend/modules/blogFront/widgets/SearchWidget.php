<?php

namespace frontend\modules\blogFront\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Виджет поиска постов
 */
class SearchWidget extends Widget {

	public function run() {

		$query = Yii::$app->request->get('query');

		$query = Html::encode($query);

		return $this->render('search', [
			'query' => $query,
		]);
	}

}