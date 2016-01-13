<?php

namespace common\base;

use Yii;

class Module extends \yii\base\Module {

	public function init() {
		parent::init();
		$this->controllerNamespace = 'common' . '\\modules\\' . $this->id . '\\controllers';
	}
}