<?php

namespace frontend\modules\blogFront\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Виджет поиска постов
 */
class SearchWidget extends Widget {

	const ATTR_BUTTON_CLASS = 'buttonClass';
	/** @var string Класс кнопки поиска */
	public $buttonClass = 'icons icons-search';
	/** @var string Поисковый запрос */
	public $query;

	public function run() {

		$query = Yii::$app->request->get('query');

		$this->query = Html::encode($query);

		return $this->render('search', [
			'widget' => $this,
		]);
	}

}