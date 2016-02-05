<?php

namespace backend\modules\editor\assets;


use yii\web\AssetBundle;

class TinyMCEAsset extends AssetBundle {

	public $sourcePath = '@bower/tinymce';

	public $js = [
		'tinymce.js',
	];
}