<?php

namespace frontend\modules\commentFront;

use common\modules\comment\Comment;
use frontend\modules\commentFront\widgets\CommentWidget;

class CommentFront extends Comment {

	/**
	 * Получение виджета комментариев
	 *
	 * @param int $entityId     Id сущности
	 *
	 * @param int $entityItemId Id элемента сущности
	 *
	 * @return CommentWidget
	 */
	public function getCommentWidget($entityId, $entityItemId) {

		return new CommentWidget([
			'entityId'     => $entityId,
			'entityItemId' => $entityItemId,
		]);
	}

}