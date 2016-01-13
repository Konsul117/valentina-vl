<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\rbac\ManagerInterface;


/**
 * Первичная установка приложения
 */
class InitController extends Controller {
	/**
	 * Создание учётной записи администратора
	 */
	public function actionCreateUser() {

		$this->stdout('Добавление пользователя' . PHP_EOL);

		$this->stdout('Логин: ' . PHP_EOL);

		$username = Console::stdin();

		$this->stdout('E-mail: ' . PHP_EOL);

		$email = Console::stdin();

		$this->stdout('Пароль: ' . PHP_EOL);

		$password = Console::stdin();

		$user           = new User();
		$user->username = $username;
		$user->email    = $email;
		$user->setPassword($password);
		$user->generateAuthKey();

		if ($user->validate() === false) {
			$this->stdout('Ошибки при вводе данных: ' . print_r($user->getErrors(), true));

			return ;
		}

		if ($user->save()) {
			$this->stdout('Пользователь добавлен' . PHP_EOL, Console::FG_GREEN);
			/** @var ManagerInterface $auth */
//			$auth = Yii::$app->authManager;
//			$authorRole = $auth->getRole(AclHelper::ROLE_ADMIN);
//			$auth->assign($authorRole, $user->id);
//
//			$this->stdout('Права пользователя добавлены' . PHP_EOL, Console::FG_GREEN);
		}
	}

}