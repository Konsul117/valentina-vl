<?php
namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\StringHelper;
use common\base\Module;
use common\modules\core\backend\models\ModuleSettingsForm;

/**
 * Компонент для управления и проверки модулей.
 *
 * @property-read ModuleManagerModules $modules Провайдер включённых модулей
 */
class ModuleManager extends Component implements BootstrapInterface {
	/** @var Module[] Коллекция активных модулей. В качестве ключа используется реализуемый интерфейс. */
	private $_enabledModules;

	/** @var Module[] Коллекция модулей, зависимых от определённого интерфейса (ключ - имя интерфейса). */
	private $_dependants;

	/** @var Module[] Массив инициализированных модулей. */
	protected $_availableModules;

	/** @var ModuleManagerModules Провайдер включённых модулей. */
	private $_modules;

	/**
	 * @inheritdoc
	 */
	public function bootstrap($app) {
		$this->_initModules();
//		$this->_initEnabledModules();
//		$this->_initDependants();

		// -- Выполняем действие после инициализации всех модулей
//		foreach ($this->getAvailableModules() as $module) {
//			$module->afterInit();
//		}
		// -- -- -- --

		// -- Проверяем, соблюдены ли зависимости, но только на frontend'е
//		if (Yii::$app->configManager->isFrontendEntryPoint()) {
//			$errors = [];
//			foreach ($this->_enabledModules as $module) {
//				$result = $this->_isDependenciesResolved($module);
//				if (true !== $result) {
//					$errors[] = '[' . $module->id . '] ' . $result;
//				}
//			}
//
//			if (0 !== count($errors)) {
//				throw new InvalidConfigException('Ошибка инициализации модулей.' . PHP_EOL . implode(PHP_EOL, $errors));
//			}
//		}
		// -- -- -- --
	}

	/**
	 * Инициализация массива модулей.
	 */
	private function _initModules() {
		$modules = [];

		// -- Отсеиваем сторонние модули - всё равно их настроить нельзя
		foreach (array_keys(Yii::$app->getModules()) as $moduleName) {
			$module = Yii::$app->getModule($moduleName);
			if ($module instanceof Module) {
				$modules[$moduleName] = $module;
			}
		}
		// -- -- -- --

		$this->_availableModules = $modules;
	}

	/**
	 * Инициализация включенных модулей.
	 */
	private function _initEnabledModules() {
		$this->_enabledModules = [];

		foreach ($this->_availableModules as $module) {
			if ($module->enabled) {
				foreach ($module->getImplements() as $interface) {
					if (array_key_exists($interface, $this->_enabledModules)) {
						throw new InvalidConfigException('Включено несколько модулей с интерфейсом "' . StringHelper::basename($interface) . '": "' . $module->id . '" и "' . $this->_enabledModules[$interface]->id . '".');
					}
					$this->_enabledModules[$interface] = $module;
				}
			}
		}
	}

	/**
	 * Инициализация списка зависимостей от интерфейсов.
	 */
	private function _initDependants() {
		$this->_dependants = [];

		foreach ($this->_availableModules as $module) {
			foreach ($module->getDependencies() as $dependency) {
				if (false === array_key_exists($dependency, $this->_dependants)) {
					$this->_dependants[$dependency] = [];
				}
				$this->_dependants[$dependency][] = $module;
			}
		}
	}

	/**
	 * Проверка на удовлетворение зависимостей при включении данного модуля.
	 * @param Module $module Модуль, зависимости которого нужно проверить
	 * @return bool|string Возвращает TRUE, если нет ошибок или текст ошибки
	 */
	private function _isDependenciesResolved(Module $module) {
		$dependencies = $module->getDependencies();
		if (0 === count($dependencies)) {
			return true;
		}

		$missedDependencies = [];

		foreach ($dependencies as $dependency) {
			if (false === array_key_exists($dependency, $this->_enabledModules)) {
				$missedDependencies[] = $dependency;
			}
		}

		if (0 === count($missedDependencies)) {
			return true;
		}

		return 'Не удовлетворены следующие зависимости: ' . implode(', ', $missedDependencies);
	}

	/**
	 * Проверка конфликтов модуля (есть ли другие включённые модули, реализуемые данный интерфейс).
	 *
	 * @param Module $module Модуль, конфликты которого нужно проверить
	 * @return bool|string Возвращает TRUE, если нет ошибок или текст ошибки
	 */
	private function _checkConflicts(Module $module) {
		$conflictedModules = [];
		$implements = $module->getImplements();
		foreach ($implements as $interface) {
			if (array_key_exists($interface, $this->_enabledModules)) {
				$conflictedModules[] = $this->_enabledModules[$interface]->id;
			}
		}

		if (0 === count($conflictedModules)) {
			return true;
		}

		return 'Следующие модули конфликтуют с данным модулем: ' . implode(', ', $conflictedModules);
	}

	/**
	 * Проверка зависимости других модулей от указанного.
	 *
	 * @param Module $module Модуль, зависимость от которого необходимо проверить
	 * @return bool|string Возвращает TRUE, если нет ошибок или текст ошибки
	 */
	private function _checkDependants(Module $module) {
		$implements = $module->getImplements();

		$errorModules = [];

		foreach ($implements as $interface) {
			if (array_key_exists($interface, $this->_dependants)) {
				foreach ($this->_dependants[$interface] as $dependantModule) {/** @var Module $dependantModule */
					if ($dependantModule->enabled) {
						$errorModules[] = $dependantModule->id;
					}
				}
			}
		}

		if (0 === count($errorModules)) {
			return true;
		}

		return 'Следующие модули зависят от данного модуля: ' . implode(', ', $errorModules);
	}

	/**
	 * Проверка, доступен ли модуль с указанным интерфейсом или нет.
	 *
	 * @param string $interface Имя интерфейса
	 * @return bool
	 */
	public function isModuleAvailable($interface) {
		return (null !== $this->getModule($interface));
	}

	/**
	 * Получаем полный список включенных модулей.
	 *
	 * @return \common\base\Module[]
	 */
	public function getEnabledModules() {
		return $this->_enabledModules;
	}

	/**
	 * Получение списка всех доступных модулей.
	 *
	 * @return \common\base\Module[]
	 */
	public function getAvailableModules() {
		return $this->_availableModules;
	}

	/**
	 * Получение объекта модуля исходя из интерфейса, который его описывает.
	 *
	 * @param string $interface Имя интерфейса
	 * @return Module|null Объект модуля или NULL, если такой модуль не найден или выключен
	 */
	public function getModule($interface) {
		if (false === array_key_exists($interface, $this->_enabledModules)) {
			return null;
		}
		return $this->_enabledModules[$interface];
	}

	/**
	 * Получение провайдера включённых модулей.
	 * Вместо прямого вызова метода необходимо обращаться к свойству $this->modules.
	 *
	 * @return ModuleManagerModules
	 */
	protected function getModules() {
		if (null === $this->_modules) {
			$modules = [];
			foreach ($this->_enabledModules as $interface => $module) {
				// -- Извлекаем из названия интерфейса только его оконцовку, преобразуя в camelCase
				$key = StringHelper::basename($interface);
				$key = mb_strtolower(mb_substr($key, 0, 1)) . mb_substr($key, 1);
				// -- -- -- --

				$modules[$key] = $module;
			}

			$this->_modules = new ModuleManagerModules($modules);
		}

		return $this->_modules;
	}

	/**
	 * Получение объекта модуля по его имени.
	 * Необходимо, например, чтобы работать с отключённым модулем (получать информацию о нём и управлять им).
	 *
	 * @param string $moduleName
	 * @return Module|null
	 */
	public function getModuleByName($moduleName) {
		if (array_key_exists($moduleName, $this->_availableModules)) {
			return $this->_availableModules[$moduleName];
		}
		return null;
	}
}