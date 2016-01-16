<?php

namespace backend\modules\editor;

use backend\modules\editor\widgets\EditorWidget;
use common\base\Module;
use yii\base\Model;
use yii\base\Widget;
use yii\widgets\ActiveForm;

/**
 * Модуль редактора
 */
class Editor extends Module {

	/**
	 * Получение виджета редактора
	 *
	 * @param ActiveForm $form
	 * @param Model      $model
	 * @param string     $identAttribute   аттрибут идентификатора модели
	 * @param string     $contentAttribute аттрибут контента модели
	 * @return Widget
	 */
	public function getEditorWidget(ActiveForm $form, Model $model, $identAttribute, $contentAttribute) {
		$widget = new EditorWidget([
			'form'             => $form,
			'model'            => $model,
			'identAttribute'   => $identAttribute,
			'contentAttribute' => $contentAttribute,
		]);

		return $widget;
	}

}