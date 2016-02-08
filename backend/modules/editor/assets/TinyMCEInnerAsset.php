<?php
/**
 * Created by PhpStorm.
 * User: konsul
 * Date: 08.02.16
 * Time: 22:15
 */

namespace backend\modules\editor\assets;


use yii\web\AssetBundle;

class TinyMCEInnerAsset extends AssetBundle {

	public $sourcePath = __DIR__;

	public $css = [
		'css/editor_inner.css',
	];

}