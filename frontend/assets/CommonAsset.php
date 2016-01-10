<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle {

	public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

	public $sourcePath = '@themeRoot';

	public $css		 = [
		'css/valentina-vl-vendor.css',
		'css/valentina-vl-main.css',
	];

	public $js		 = [
		'js/valentina-vl-vendor.js',
		'js/valentina-vl-main.js',
	];

	public $depends	 = [
	];

	public function init() {
		parent::init();
		
		if (YII_ENV === 'dev') {
			$this->js[] = '//localhost:35727/livereload.js';
		}
	}

}
