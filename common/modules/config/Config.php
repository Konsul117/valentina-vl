<?php

namespace common\modules\config;

use common\base\Module;
use common\modules\config\models\ConfigParam;
use Yii;
use yii\caching\TagDependency;

/**
 * Класс конфигурации
 */
class Config extends Module {

	/** @var  ConfigParam[] */
	protected $configParams;

	/**
	 * Получение параметра кофигурации
	 *
	 * @param $param
	 *
	 * @return string|null
	 */
	public function getParamValue($param) {
		$this->loadParams();

		if (isset($this->configParams[$param])) {
			return $this->configParams[$param]->value;
		}

		return null;
	}

	/**
	 * Получение списка всех параметров
	 *
	 * @return array
	 */
	protected function loadParams() {
		if ($this->configParams !== null) {
			return;
		}

		$cacheKey = __METHOD__;

		$params = Yii::$app->cache->get($cacheKey);

		if ($params === false) {
			$params = ConfigParam::find()
				->indexBy(ConfigParam::ATTR_NAME)
				->all();

			Yii::$app->cache->set($cacheKey, $params, 2 * 3600, new TagDependency(['tags' => [ConfigParam::class]]));
		}

		$this->configParams = $params;
	}

	/**
	 * Назначение параметра
	 *
	 * @param string $param
	 * @param string $value
	 *
	 * @return bool
	 */
	public function setParamValue($param, $value) {
		$this->loadParams();

		if (isset($this->configParams[$param])) {
			$paramModel = $this->configParams[$param];
		}
		else {
			$paramModel           = new ConfigParam();
			$paramModel->name     = $param;
			$this->params[$param] = $paramModel;
		}

		$paramModel->value = $value;

		return $paramModel->save();
	}

}