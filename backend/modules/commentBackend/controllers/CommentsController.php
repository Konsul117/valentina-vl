<?php

namespace backend\modules\commentBackend\controllers;

use backend\modules\commentBackend\models\CommentSearch;
use Yii;
use yii\base\Controller;

/**
 * Контроллер просмотра комментариев в бэкэнде
 */
class CommentsController extends Controller {

	/**
	 * Главная страница - список комментариев
	 *
	 * @return string
	 */
	public function actionIndex() {
		$searchModel  = new CommentSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$dataProvider->setPagination(['pageSize' => 10]);
		$dataProvider->setSort(['defaultOrder' => [CommentSearch::ATTR_INSERT_STAMP => SORT_DESC]]);

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

}