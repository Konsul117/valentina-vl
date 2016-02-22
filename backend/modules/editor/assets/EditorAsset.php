<?php

namespace backend\modules\editor\assets;

use yii\web\AssetBundle;

class EditorAsset extends AssetBundle {

	public $sourcePath = __DIR__;

	public $js = [
		'js/tinymce_init.js',
	];

	public $css = [
		'css/editor.css',
	];

	public $depends = [
		TinyMCERus::class,
	];

}