<?php

namespace backend\modules\image\assets;

use yii\web\AssetBundle;

/**
 * Бандл ассетов для загрузчика изображений
 */
class ImageUploadAsset extends AssetBundle {

	public $sourcePath = __DIR__;

	public $js = [
		'js/jquery.upload_image.js',
		'js/dropzone_customize.js'
	];

	public $depends = [
		DropzoneAsset::class,
	];

}