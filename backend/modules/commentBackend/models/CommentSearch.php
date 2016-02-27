<?php

namespace backend\modules\commentBackend\models;

use common\modules\comment\models\Comment;
use yii\data\ActiveDataProvider;

/**
 * Расширение модели комментариев для поиска в бэкэнде
 */
class CommentSearch extends Comment {

	/**
	 * Поиск
	 *
	 * @param array $params
	 * @param int   $categoryId
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = $this->find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere(['like', static::tableName() . '.' . static::ATTR_NICKNAME, $this->nickname]);
		$query->andFilterWhere(['like', static::tableName() . '.' . static::ATTR_CONTENT, $this->content]);

		$query->andFilterWhere([
			static::tableName() . '.' . static::ATTR_ID           => $this->id,
			static::tableName() . '.' . static::ATTR_INSERT_STAMP => $this->insert_stamp,
			static::tableName() . '.' . static::ATTR_UPDATE_STAMP => $this->update_stamp,
		]);

		return $dataProvider;
	}

	public static function tableName() {
		return Comment::tableName();
	}

}