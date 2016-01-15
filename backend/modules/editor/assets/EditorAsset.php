<?php

namespace backend\modules\editor\assets;

use yii\web\AssetBundle;

class EditorAsset extends AssetBundle {

	public $sourcePath = __DIR__;

	public $js = [
		'js/jquery.upload_image.js',
		'js/dropzone_customize.js'
	];

	public $css = [
		'css/editor.css',
	];

	public $depends = [
		CKEditorAsset::class,
		DropzoneAsset::class,
	];

}