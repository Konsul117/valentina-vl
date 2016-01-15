<?php

namespace backend\modules\editor\assets;

use yii\web\AssetBundle;

/**
 * Asset для плагина jquery-ui
 */
class CKEditorAsset extends AssetBundle {

	public $sourcePath = '@bower/ckeditor';

	public $js = [
		'ckeditor.js',
		'plugins/justify/plugin.js',
	];

	public $css = [
	];

}