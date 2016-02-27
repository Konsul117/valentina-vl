<?php

namespace backend\modules\commentBackend\widgets;

use backend\modules\commentBackend\assets\CommentAsset;
use common\modules\comment\models\Comment;
use yii\base\Widget;

/**
 * Виджет последних комментариев
 */
class LastCommentsWidget extends Widget {

	/** @var Comment[] */
	public $lastComments;

	/** @var int Количество выводимых последних комментариев */
	public $limit = 10;

	/**
	 * @inheritdoc
	 */
	public function run() {

		CommentAsset::register($this->view);

		$this->lastComments = Comment::find()
			->orderBy([Comment::ATTR_INSERT_STAMP => SORT_DESC])
			->limit($this->limit)
			->all();

		return $this->render('last-comments', [
			'widget' => $this
		]);
	}

}