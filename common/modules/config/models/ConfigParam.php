<?php

namespace common\modules\config\models;

use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * Конфигурация параметров
 *
 * @property string $name  Имя параметра
 * @property string $value Значение параметра
 */
class ConfigParam extends ActiveRecord {

	/** Имя параметра */
	const ATTR_NAME = 'name';

	/** Значение параметра */
	const ATTR_VALUE = 'value';

	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);

		TagDependency::invalidate(Yii::$app->cache, __CLASS__);
	}

}