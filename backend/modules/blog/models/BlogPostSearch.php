<?php

namespace backend\modules\blog\models;

use common\modules\blog\models\BlogCategory;
use common\modules\blog\models\BlogPost;
use yii\data\ActiveDataProvider;

class BlogPostSearch extends BlogPost {

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[[
				static::ATTR_ID,
				static::ATTR_TITLE,
				static::ATTR_TAGS,
				static::ATTR_IS_PUBLISHED,
				static::ATTR_INSERT_STAMP,
				static::ATTR_UPDATE_STAMP,
			], 'safe'],
		];
	}

	public static function tableName() {
		return BlogPost::tableName();
	}

	/**
	 * Поиск
	 *
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search($params) {
		$query = $this->find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$query->select($this->tableName() . '.*, ' . BlogCategory::tableName() . '.' . BlogCategory::ATTR_TITLE . ' AS category_title');

		$query->leftJoin(BlogCategory::tableName(),
			BlogCategory::tableName() . '.' . BlogCategory::ATTR_ID . ' = ' . $this->tableName() . '.' . static::ATTR_CATEGORY_ID
		);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere(['like', static::tableName() . '.' . static::ATTR_TAGS, $this->tags]);
		$query->andFilterWhere(['like', static::tableName() . '.' . static::ATTR_TITLE, $this->title]);

		$query->andFilterWhere([
			static::tableName() . '.' . static::ATTR_ID           => $this->id,
			static::tableName() . '.' . static::ATTR_IS_PUBLISHED => $this->is_published,
			static::tableName() . '.' . static::ATTR_INSERT_STAMP => $this->insert_stamp,
			static::tableName() . '.' . static::ATTR_UPDATE_STAMP => $this->update_stamp,
		]);

		$query->orderBy([static::ATTR_INSERT_STAMP => SORT_DESC]);

		return $dataProvider;
	}

}