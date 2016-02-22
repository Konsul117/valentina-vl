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
	 * @param string     $id               Id textarea
	 * @param string     $contentAttribute аттрибут контента модели
	 * @return Widget
	 */
	public function getEditorWidget(ActiveForm $form, Model $model,  $id, $contentAttribute) {
		$widget = new EditorWidget([
			'form'             => $form,
			'model'            => $model,
			'id'               => $id,
			'contentAttribute' => $contentAttribute,
		]);

		return $widget;
	}

}