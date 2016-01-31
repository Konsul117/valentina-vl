<?php

namespace frontend\modules\commentFront\widgets;

use frontend\modules\commentFront\models\CommentFront;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;

class CommentWidget extends Widget {

	const TOKEN_NAME = 'comment-form-token';
	/**
	 * Id сущности
	 *
	 * @var int
	 */
	public $entityId;
	/**
	 * Id элемента сущности
	 *
	 * @var int
	 */
	public $entityItemId;

	public function run() {
		if (!$this->entityId || !$this->entityItemId) {
			throw new InvalidConfigException('Отсутствует id сущности или строки сущности');
		}

		$comment_added = null;

		$newComment = new CommentFront();

		if (Yii::$app->request->isPost) {
			$comment_added = $this->post();
		}

		Yii::$app->session->set(static::TOKEN_NAME, Yii::$app->request->csrfToken);

		$commentsQuery = CommentFront::find()
			->where([
				CommentFront::ATTR_RELATED_ENTITY_ID      => $this->entityId,
				CommentFront::ATTR_RELATED_ENTITY_ITEM_ID => $this->entityItemId,
				CommentFront::ATTR_IS_PUBLISHED           => true,
			])
			->orderBy([CommentFront::ATTR_INSERT_STAMP => SORT_DESC]);

		$comments = $commentsQuery->all();

		return $this->render('comment', [
			'comment_added' => $comment_added,
			'comments'      => $comments,
			'newComment'    => $newComment,
		]);
	}

	protected function post() {
		$prevToken = Yii::$app->session->get(static::TOKEN_NAME);

		$newComment = new CommentFront();

		if (($prevToken !== null) && ($prevToken !== Yii::$app->request->getBodyParam(Yii::$app->request->csrfParam))) {
			//если предыдущий токен есть и не равен полученному, то бородим
			return false;
		}

		$newComment->scenario               = CommentFront::SCENARIO_USER_ADD;
		$newComment->related_entity_id      = $this->entityId;
		$newComment->related_entity_item_id = $this->entityItemId;

		if ($newComment->load(Yii::$app->request->post()) && $newComment->save()) {
			return true;
		}

		return false;
	}


}