<?php
namespace common\components;

use yii\web\UrlRule;
use Yii;
use GlobIterator;
use InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * Сборщик конфигов.
 */
class ConfigCollector {
	/** Константа ключа в куках, в котором хранится информация о режиме отладки. */
	const DEBUG_MODE_COOKIE = 'ConfigManager_DEBUG_MODE';

	/** Тип входной точки: админка. */
	const ENTRY_POINT_BACKEND = 'backend';
	/** Тип входной точки: клиентская часть. */
	const ENTRY_POINT_FRONTEND = 'frontend';
	/** Тип входной точки: консоль. */
	const ENTRY_POINT_CONSOLE = 'console';
	/** Тип входной точки: api. */
	const ENTRY_POINT_API = 'api';

	/** @var string Путь до директории, откуда запущено приложение. */
	private static $_runFromPath;

	/** @var string Корень всего репозитория. */
	private static $_repositoryRootPath;

	/** @var string Название входной точки (console/backend/frontend). */
	private static $_entryPoint;

	/** @var array Базовая конфигурация (без конфигурации модулей). */
	private static $_baseConfig;

	/** @var array Базовая конфигурация плюс конфигурации модулей и компонентов. */
	private static $_mergedConfig;

	/** @var bool Использовать ли кэширование конфига или нет. */
	private static $_useConfigCaching;

	/** @var string Пространство для хранения настроек (чтобы разделять их между сайтами). */
	private static $_settingsNamespace;

	/**
	 * Получение конфига для указанного приложения (входной точки).
	 *
	 * @return array
	 */
	public static function getApplicationConfig($repositoryPath = null, $entryPoint = null) {
		try {
			// -- Определяем пути, с которыми будем работать
			if (defined('STDIN')) {
				static::$_runFromPath = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/' . static::ENTRY_POINT_CONSOLE);
			}
			else {
				static::$_runFromPath = realpath(dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
			}

			if (null !== $repositoryPath) {
				static::$_repositoryRootPath = $repositoryPath;
				static::$_runFromPath = $repositoryPath . '/' . $entryPoint;
			}
			else {
				static::$_repositoryRootPath = realpath(dirname(static::$_runFromPath));

			}
			// -- -- -- --

			// -- Определяем входную точку
			static::$_entryPoint = static::ENTRY_POINT_FRONTEND;

			if (
					(0 === strpos(@$_SERVER['SERVER_NAME'], 'backend.'))
					|| (0 === strpos(@$_SERVER['SERVER_NAME'], 'console.')) // У консоли через веб только одна точка входа - backend
			) {
				static::$_entryPoint = static::ENTRY_POINT_BACKEND;
			}
			elseif (static::ENTRY_POINT_CONSOLE === basename(static::$_runFromPath)) {
				static::$_entryPoint = static::ENTRY_POINT_CONSOLE;
			}
			// -- -- -- --

			static::getBaseConfig();

			$config = static::getMergedConfig();

			// -- Вырезаем подключение модуля отладки из конфига
//			if (false === static::isDebugMode()) {
//				if (array_key_exists('bootstrap', $config)) {
//					foreach ($config['bootstrap'] as $i => $name) {
//						if ('debug' === $name) {
//							unset($config['bootstrap'][$i]);
//						}
//					}
//				}
//				unset($config['modules']['debug']);
//			}
//			else {
//				$config = ArrayHelper::merge($config, [
//					'modules' => [
//						'debug' => [
//							'allowedIPs' => [
//								$_SERVER['REMOTE_ADDR'],
//							],
//						],
//					],
//				]);
//			}
			// -- -- -- --

			return $config;
		} catch (\Exception $e) {
			header('HTTP/1.0 500 Error Loading Configuration');
			if (true === static::isDebugMode()) {
				echo '<b>' . $e->getMessage() . '</b>';
				echo '<pre>';
				echo $e->getFile() . ':' . $e->getLine();
				echo PHP_EOL . PHP_EOL;
				echo $e->getTraceAsString();
				echo '</pre>';
			}
			else {
				echo 'Ошибка конфигурации: L' . $e->getLine();
			}
			die;
		}
	}

	/**
	 * Получение пути, где хранятся кэши конфигов.
	 *
	 * @param bool $includeSettingsNamespace Включить ли в качестве суффикса название пространства настроек или нет (то есть, включить ли alias настроек или нет)
	 * @return string
	 */
	public static function getCacheFileName($includeSettingsNamespace = false) {
		$path = static::$_repositoryRootPath . '/common/config/cached/' . basename(static::$_runFromPath) . '-' . static::$_entryPoint;
		if (true === $includeSettingsNamespace) {
			$path .= '[' . static::$_settingsNamespace . ']';
		}
		return $path . '.php';
	}

	/**
	 * Получение базового конфига (без конфигурации модулей).
	 *
	 * @param bool $useCache Использовать ли кэширование или нет
	 * @return array
	 */
	public static function getBaseConfig($useCache = true) {
		if (false === $useCache || null === static::$_baseConfig) {
			// -- Проверяем, можно ли взять закэшированный конфиг или нет
			$cacheFilename = static::getCacheFileName();

			if (true === $useCache && static::useConfigCaching() && file_exists($cacheFilename)) {
				$config = require($cacheFilename);
			}
			else {
				// -- Основные файлы конфигурации
				$mergeFiles = [
					static::$_repositoryRootPath . '/common/config/common.php',
					static::$_repositoryRootPath . '/common/config/common-local.php',
					static::$_repositoryRootPath . '/common/config/' . static::$_entryPoint . '.php',
					static::$_repositoryRootPath . '/common/config/' . static::$_entryPoint . '-local.php',
					static::$_runFromPath . '/config/common.php',
					static::$_runFromPath . '/config/common-local.php',
					static::$_runFromPath . '/config/' . static::$_entryPoint . '.php',
					static::$_runFromPath . '/config/' . static::$_entryPoint . '-local.php',
				];
				// -- -- -- --

				// -- Получаем всю конфигурацию, подключая поочерёдно данные из всех файлов конфигурации
				$config = [];

				foreach ($mergeFiles as $file) {
					$file = realpath($file);
					if (false !== $file && file_exists($file)) {
						$config = ArrayHelper::merge($config, require($file));
					}
				}
				// -- -- -- --

				// -- Дополняем конфиг правилами для UrlManager'а
				static::_pushUrlRules($config, [
					static::$_repositoryRootPath . '/common/config/url-rules.php',
					static::$_runFromPath . '/config/url-rules.php',
				]);
				// -- -- -- --

				// -- Дополняем конфиг необходимыми параметрами
				$config['basePath'] = static::$_runFromPath;
//				$config['controllerNamespace'] = basename(static::$_runFromPath) . '\\' . static::$_entryPoint . '\\controllers';
				$config['controllerNamespace'] = basename(static::$_runFromPath) . '\\controllers';
				$config['bootstrap'] = array_unique($config['bootstrap']);
				// -- -- -- --
			}
			// -- -- -- --

			// -- Определяем пространство для хранения настроек модулей
			if (false === isset($config['id'])) {
				throw new InvalidArgumentException('Не указан параметр id');
			}
			static::$_settingsNamespace = $config['id'];
			// -- -- -- --

			static::$_baseConfig = $config;
		}

		return static::$_baseConfig;
	}

	/**
	 * Получение всей конфигурации, включая конфигурацию модулей.
	 *
	 * @param bool $useCache Использовать ли кэширование или нет
	 * @return array
	 */
	public static function getMergedConfig($useCache = true) {
		if (false === $useCache || null === static::$_mergedConfig) {
			// -- Проверяем, можно ли взять закэшированный конфиг или нет
			$cacheFilename = static::getCacheFileName(true);

			if (true === $useCache && static::useConfigCaching() && file_exists($cacheFilename)) {
				$config = require($cacheFilename);
			}
			else {
				//				$enabledModules = static::_getEnabledModules();

				//				$enabledModules = ['seoScanner'];

				// -- Проходимся по всем модулям и находим их конфиги
				$urlRuleFiles = [];
				$mergeFiles   = [];

				foreach (['common', static::$_entryPoint] as $enityPoint) {

					foreach (new GlobIterator(static::$_repositoryRootPath . '/' . $enityPoint . '/modules/*') as $globItem) {
						/** @var GlobIterator $globItem */
						if (is_dir($globItem->getPathname()) && '.' !== $globItem->getPathname() && '..' !== $globItem->getPathname()) {
							// -- На frontend'е не подключаем конфиги отключённых модулей
							//						if (false === isset($enabledModules[mb_strtolower($globItem->getFilename())]) && static::ENTRY_POINT_BACKEND !== static::getEntryPoint()) {
							//							continue;
							//						}
							// -- -- -- --

							// -- Заносим список файлов, хранящих правила для urlManager'а
							$urlRuleFiles[] = $globItem->getPathname() . '/config/url-rules.php';
//							$urlRuleFiles[] = static::$_runFromPath . '/modules/' . $globItem->getFilename() . '/config/url-rules.php';
							// -- -- -- --

							$mergeFiles[] = $globItem->getPathname() . '/config/common.php';// Основной файл конфига
							$mergeFiles[] = $globItem->getPathname() . '/config/common-local.php';// Основной локальный файл конфига
							$mergeFiles[] = $globItem->getPathname() . '/config/' . static::$_entryPoint . '.php';// Файл конфига для текущего типа приложения
							$mergeFiles[] = $globItem->getPathname() . '/config/' . static::$_entryPoint . '-local.php';// Локальный файл конфига для текущего типа приложения

							$mergeFiles[] = static::$_runFromPath . '/modules/' . $globItem->getFilename() . '/config/common.php';// Основной файл конфига для текущего сайта
							$mergeFiles[] = static::$_runFromPath . '/modules/' . $globItem->getFilename() . '/config/common-local.php';// Основной локальный файл конфига для текущего сайта
							$mergeFiles[] = static::$_runFromPath . '/modules/' . $globItem->getFilename() . '/config/' . static::$_entryPoint . '.php';// Файл конфига для текущего сайта для текущего типа приложения
							$mergeFiles[] = static::$_runFromPath . '/modules/' . $globItem->getFilename() . '/config/' . static::$_entryPoint . '-local.php';// Локальный файл конфига для текущего сайта для текущего типа приложения
						}
					}
				}
				// -- -- -- --

				// -- Получаем всю конфигурацию, подключая поочерёдно все файлы конфигов
				$config = [];

				foreach ($mergeFiles as $file) {
					$file = realpath($file);
					if (false !== $file && file_exists($file)) {
						$partialConfig = require($file);

						// -- Извлекаем название модуля
//						$moduleName = basename(dirname(dirname($file)));
						// -- -- -- --

						// -- Корректируем настройки в зависимости от того, включён ли модуль или нет
//						$enabled = in_array(mb_strtolower($moduleName), $enabledModules);
//
//						if (false === $enabled) {
//							foreach ($partialConfig as $key => $value) {
//								if ('modules' !== $key) {
//									unset($partialConfig[$key]);
//								}
//							}
//						}
						// -- -- -- --

						$config = ArrayHelper::merge($config, $partialConfig);
					}
				}
				// -- -- -- --

				// -- Проходимся по всем модулям и проставляем им флаг, включён ли модуль или нет
//				foreach ($config['modules'] as $moduleName => $moduleConfig) {
//					$config['modules'][$moduleName]['enabled'] = in_array(mb_strtolower($moduleName), $enabledModules);
//				}
				// -- -- -- -

				// -- Сливаем настройки модулей с основными настройками приложения
				$config = ArrayHelper::merge(static::$_baseConfig, $config);
				// -- -- -- --

				// -- Дополняем конфиг правилами для UrlManager'а
				static::_pushUrlRules($config, $urlRuleFiles);
				// -- -- -- --
			}
			// -- -- -- --

			static::$_mergedConfig = $config;
		}

		return static::$_mergedConfig;
	}

	/**
	 * Получение списка правил для UrlManager'а.
	 *
	 * @param array $config Собранный конфиг, куда добавить правила для urlManager'а
	 * @param string[] $files Список файлов, откуда взять правила
	 * @return array
	 */
	private static function _pushUrlRules(&$config, array $files) {
		$rules = [];

		// -- Проходимся по всем файлам
		foreach ($files as $file) {
			$file = realpath($file);
			if (false !== $file && file_exists($file)) {
				$fileRules = require($file);

				foreach ($fileRules as $entryPointName => $entryPointRules) {
					foreach ($entryPointRules as $entryPointRule) {
						if ($entryPointName !== static::$_entryPoint) {
							$entryPointRule['route']	= $entryPointName . '/' . $entryPointRule['route'];// Добавляем точку входа в роут, чтобы они отличались
							$entryPointRule['mode']		= UrlRule::CREATION_ONLY;// Указываем, что роут используется ТОЛЬКО для создания URL, а не для парсинга
						}
						$rules[$entryPointName . ':' . md5($entryPointRule['pattern'])] = $entryPointRule;// Генерируем уникальное имя исходя из роута, чтобы их можно было переопределить из других модулей
					}
				}
			}
		}
		// -- -- -- --

		// -- Сливаем роуты с уже существующими роутами
		if (isset($config['components']['urlManager']['rules'])) {
			$rules = ArrayHelper::merge($rules, $config['components']['urlManager']['rules']);
		}
		// -- -- -- --

		$config['components']['urlManager']['rules'] = $rules;
	}

	/**
	 * Удаление всех закешированных файлов настроек.
	 */
	public static function clearCache() {
		$mask = dirname(static::getCacheFileName()) . '/' . basename(static::$_runFromPath) . '-*.php';
		foreach (new GlobIterator($mask) as $globItem) {/** @var GlobIterator $globItem */
			unlink($globItem->getPathname());
		}
	}

	/**
	 * Получение состояния режима отладки.
	 * Данные берутся из куки, и если в куках указан текущий IP пользователя, то значит включён режим отладки.
	 *
	 * @return bool Включён или отключён режим отладки
	 */
	public static function isDebugMode() {
		return true;
		// -- Если есть кука, проверяем, какой там IP адрес
		if (isset($_COOKIE[static::DEBUG_MODE_COOKIE])) {
			return ('true' === $_COOKIE[static::DEBUG_MODE_COOKIE]);
		}

//		// -- Проверяем, есть ли в конфиге модуль отладки или нет
		if (false === isset(static::$_baseConfig['modules']['debug'])) {
			return false;
		}

		// -- Определяем список разрешённых IP адресов
		$allowedIPs = ['127.0.0.1', '::1'];// По-умолчанию, только localhost
		if (is_array(static::$_baseConfig['modules']['debug']) && array_key_exists('allowedIPs', static::$_baseConfig['modules']['debug'])) {
			$allowedIPs = static::$_baseConfig['modules']['debug']['allowedIPs'];// Если есть список IP адресов в конфиге, то берём именно их
		}
//		// -- -- -- --
//
//		// -- Определяем, разрешён ли IP адрес пользователя или нет (алгоритм взят из \yii\debug\Module, чтобы поддержать совместимость)
//		if (isset($_SERVER['REMOTE_ADDR'])) {
//			$ip = $_SERVER['REMOTE_ADDR'];
//			foreach ($allowedIPs as $filter) {
//				if ('*' === $filter || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos))) {
//					return true;
//				}
//			}
//		}
//		// -- -- -- --
//
//		return false;
	}

	/**
	 * Переключение режима отладки.
	 * Данные записываются в куку.
	 *
	 * @param bool $mode Включение или отключение режима отладки
	 */
	public static function setDebugMode($mode) {
		// -- Определяем домен верхнего уровня, чтобы прокинуть куку на все поддомены
		$domain = Yii::$app->request->hostInfo;
		$domain = '.' . $domain;// Добавляем в начало точку, так как домен может быть указан без www
		$domain = preg_replace('/.*(\.[^\.]+\.[^\.]+)$/', '\1', $domain);// Оставляем домен второго уровня, а третьего и так далее - удаляем
		// -- -- -- --

		$mode = (true === $mode ? 'true' : 'false');
		setcookie(static::DEBUG_MODE_COOKIE, $mode, time() + 15 * 60, '/', $domain, null, true);
	}

	/**
	 * Получение типа входной точки (backend, console или frontend - см. констатны ENTRY_POINT_*).
	 *
	 * @return string
	 */
	public static function getEntryPoint() {
		return static::$_entryPoint;
	}

	/**
	 * Проверяем соответсвует ли тип входной точки консоли.
	 *
	 * @author arkham.vm
	 *
	 * @return bool
	 */
	public static function isConsoleEntryPoint() {
		return (static::ENTRY_POINT_CONSOLE === static::$_entryPoint);
	}

	/**
	 * Проверяем соответсвует ли тип входной точки бэкенду.
	 *
	 * @author arkham.vm
	 *
	 * @return bool
	 */
	public static function isBackendEntryPoint() {
		return (static::ENTRY_POINT_BACKEND === static::$_entryPoint);
	}

	/**
	 * Проверяем соответсвует ли тип входной точки фронтенду.
	 *
	 * @author arkham.vm
	 *
	 * @return bool
	 */
	public static function isFrontendEntryPoint() {
		return (static::ENTRY_POINT_FRONTEND === static::$_entryPoint);
	}

	/**
	 * Получение пространства для хранения настроек (чтобы разделять настройки между сайтами).
	 *
	 * @return string
	 */
	public static function getSettingsNamespace() {
		return static::$_settingsNamespace;
	}

	/**
	 * Корень всего репозитория.
	 *
	 * @return string
	 */
	public static function getRepositoryRootPath() {
		return static::$_repositoryRootPath;
	}

	/**
	 * Использовать ли кэш конфигов или нет.
	 *
	 * @return bool
	 */
	public static function useConfigCaching() {
		if (null === static::$_useConfigCaching) {
			static::$_useConfigCaching = true;
			if (true === YII_ENV_DEV) {
				static::$_useConfigCaching = false;
			}
			if (static::ENTRY_POINT_FRONTEND !== static::$_entryPoint) {
				static::$_useConfigCaching = false;
			}
		}

		return static::$_useConfigCaching;
	}
}