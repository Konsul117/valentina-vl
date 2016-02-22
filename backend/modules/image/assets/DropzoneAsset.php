<?php

namespace backend\modules\image\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Asset-ы для плагина Dropzone
 */
class DropzoneAsset extends AssetBundle {

	public $sourcePath = '@bower/dropzone';

	public $js = [
		'dist/dropzone.js',
	];

	public $css = [
		'dist/dropzone.css',
	];

	public $depends = [
		JqueryAsset::class,
	];

}