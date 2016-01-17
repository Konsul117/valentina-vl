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

	/**
	 * @var string
	 */
	public $identAttribute;

	/**
	 * @var string
	 */
	public $contentAttribute;

	/**
	 * @var string
	 */
	public $imagesAttribute;

	/**
	 * Возможность загрузки изображений
	 * @var bool
	 */
	public $uploadImages = false;

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