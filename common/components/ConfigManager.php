<?php
namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;

/**
 * Менеджер для управления конфигами.
 * Фактически, это публичный класс для класса ConfigCollector.
 */
class ConfigManager extends Component implements BootstrapInterface {
	/** Тип входной точки: админка. */
	const ENTRY_POINT_BACKEND = ConfigCollector::ENTRY_POINT_BACKEND;
	/** Тип входной точки: клиентская часть. */
	const ENTRY_POINT_FRONTEND = ConfigCollector::ENTRY_POINT_FRONTEND;
	/** Тип входной точки: консоль. */
	const ENTRY_POINT_CONSOLE = ConfigCollector::ENTRY_POINT_CONSOLE;

	/**
	 * @inheritdoc
	 */
	public function bootstrap($app) {
		// -- Если разрешено, то кэшируем полученную конфигурацию
		if (ConfigCollector::useConfigCaching()) {
			$cacheKey = $this->getCacheKey();
			$cacheVersion = Yii::$app->memcache->get($cacheKey);
			if (false === $cacheVersion) {
				$cacheVersion = time();
				Yii::$app->memcache->set($cacheKey, $cacheVersion);
			}

			$cacheFileName = ConfigCollector::getCacheFileName();
			if (@filemtime($cacheFileName) !== $cacheVersion) {
				@file_put_contents($cacheFileName, '<?php return ' . var_export(ConfigCollector::getBaseConfig(false), true) . ';');
				@touch($cacheFileName, $cacheVersion);
			}

			$cacheFileName = ConfigCollector::getCacheFileName(true);
			if (@filemtime($cacheFileName) !== $cacheVersion) {
				@file_put_contents($cacheFileName, '<?php return ' . var_export(ConfigCollector::getMergedConfig(false), true) . ';');
				@touch($cacheFileName, $cacheVersion);
			}
		}
		// -- -- -- --
	}

	/**
	 * Получение названия ключа кэша, в котором хранится версия конфига.
	 *
	 * @return string
	 */
	protected function getCacheKey() {
		return __CLASS__;
	}

	/**
	 * Получение названия входной точки (frontend, backend или console).
	 *
	 * @return string
	 */
	public function getEntryPoint() {
		return ConfigCollector::getEntryPoint();
	}

	/**
	 * Получение имени пространства для хранения настроек.
	 *
	 * @return string
	 */
	public function getSettingsNamespace() {
		return ConfigCollector::getSettingsNamespace();
	}

	/**
	 * Получение пути до корня репозитория.
	 *
	 * @return string
	 */
	public function getRepositoryRootPath() {
		return ConfigCollector::getRepositoryRootPath();
	}

	/**
	 * Проверяем соответсвует ли тип входной точки консоли.
	 *
	 * @return bool
	 */
	public function isConsoleEntryPoint() {
		return (ConfigCollector::ENTRY_POINT_CONSOLE === $this->getEntryPoint());
	}

	/**
	 * Проверяем соответсвует ли тип входной точки бэкенду.
	 *
	 * @return bool
	 */
	public function isBackendEntryPoint() {
		return (ConfigCollector::ENTRY_POINT_BACKEND === $this->getEntryPoint());
	}

	/**
	 * Проверяем соответсвует ли тип входной точки фронтенду.
	 *
	 * @return bool
	 */
	public function isFrontendEntryPoint() {
		return (ConfigCollector::ENTRY_POINT_FRONTEND === $this->getEntryPoint());
	}

	/**
	 * Установка режима отладки.
	 *
	 * @param bool $mode Включить или отключить режим отладки
	 */
	public function setDebugMode($mode) {
		ConfigCollector::setDebugMode($mode);
	}

	/**
	 * Включён ли режим отладки или нет.
	 *
	 * @return bool
	 */
	public function isDebugMode() {
		return ConfigCollector::isDebugMode();
	}

	/**
	 * Сброс кэша настроек.
	 * Фактически, обновляется версия кэша, и при следующем обращении к странице кэш будет обновлён.
	 */
	public function clearCache() {
		$cacheKey = $this->getCacheKey();
		Yii::$app->memcache->set($cacheKey, time());
	}

	/**
	 * Получение названия проекта.
	 * Фактически, получение корневной папки, в которой был запрошен index.php.
	 *
	 * @return string
	 */
	public function getDomain() {
		return basename(Yii::$app->basePath);
	}
}