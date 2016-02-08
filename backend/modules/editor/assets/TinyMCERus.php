<?php

namespace backend\modules\editor\assets;


use yii\web\AssetBundle;

/**
 * Ассеты для редактора TimeMCE с русификацией
 */
class TinyMCERus extends AssetBundle {

	public $sourcePath = '@bower/ivan-chkv.tinymce-i18n/langs';

	public $js = [
		'ru.js',
	];

	public $depends = [
		TinyMCEAsset::class,
		TinyMCEInnerAsset::class,
	];

}