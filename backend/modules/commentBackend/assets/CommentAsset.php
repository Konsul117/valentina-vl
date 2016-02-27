<?php

namespace backend\modules\commentBackend\assets;

use yii\web\AssetBundle;

/**
 * Бандл ассетов для комментариев бэкэнда
 */
class CommentAsset extends AssetBundle {
	public $sourcePath = __DIR__;

	public $css = [
		'css/comment.css',
	];
}