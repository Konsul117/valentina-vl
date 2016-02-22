<?php
/**
 * Created by PhpStorm.
 * User: konsul
 * Date: 23.02.16
 * Time: 2:41
 */

namespace backend\modules\image\widgets;


use common\modules\image\models\Image;
use yii\base\Widget;

class ImagePanelWidget extends Widget {

	/** @var Image[] Изображения для панели */
	public $images = [];

	/**
	 * @inheritdoc
	 */
	public function run() {
		return $this->render('image-panel', [
			'widget' => $this,
		]);
	}

}