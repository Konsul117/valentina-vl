<?php

namespace backend\modules\commentBackend;

use backend\modules\commentBackend\widgets\LastCommentsWidget;
use common\modules\comment\Comment;

/**
 * Расширение модуля Comment для бэкэнда
 */
class CommentBackend extends Comment {

	/**
	 * Получение виджета последних комментариев
	 *
	 * @return LastCommentsWidget
	 */
	public function getLastCommentsWidget() {
		return new LastCommentsWidget();
	}

}