<?php

namespace backend\modules\editor\widgets;

use backend\modules\editor\assets\EditorAsset;
use yii\base\Model;
use yii\base\Widget;
use yii\widgets\ActiveForm;

/**
 * Виджет редактора
 */
class EditorWidget extends Widget {

	/**
	 * @var ActiveForm
	 */
	public $form;

	/**
	 * @var Model
	 */
	public $model;

	/** @var string Id textarea */
	public $id;

	/**
	 * Аттрибут контента модели
	 *
	 * @var string
	 */
	public $contentAttribute;

	/**
	 * @inheritdoc
	 */
	public function run() {
		$view = $this->getView();
		EditorAsset::register($view);

		return $this->render('editor-widget', [
			'widget' => $this,
		]);
	}

}