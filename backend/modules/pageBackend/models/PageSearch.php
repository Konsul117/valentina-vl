<?php

namespace backend\modules\pageBackend\models;

use common\modules\page\models\Page;
use yii\data\ActiveDataProvider;

/**
 * Расширение модели "Страницы" для поиска в бэкэнде
 */
class PageSearch extends Page {

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[[
				static::ATTR_ID,
				static::ATTR_TITLE,
				static::ATTR_IS_PUBLISHED,
				static::ATTR_INSERT_STAMP,
				static::ATTR_UPDATE_STAMP,
			], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return Page::tableName();
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

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}
		$query->andFilterWhere(['like', static::tableName() . '.' . static::ATTR_TITLE, $this->title]);

		$query->andFilterWhere([
			static::tableName() . '.' . static::ATTR_ID           => $this->id,
			static::tableName() . '.' . static::ATTR_IS_PUBLISHED => $this->is_published,
			static::tableName() . '.' . static::ATTR_INSERT_STAMP => $this->insert_stamp,
			static::tableName() . '.' . static::ATTR_UPDATE_STAMP => $this->update_stamp,
		]);

		return $dataProvider;
	}

}