<?php

namespace common\modules\blog\models;

use common\components\ErrorHelper;
use common\exceptions\ModelSaveException;
use yii\db\ActiveRecord;

/**
 * Связь постов блога и тегов
 *
 * @property int $post_id   Id поста
 * @property int $tag_id    Id тега
 * @property int $sort      Индекс сортировки
 */
class BlogPostTag extends ActiveRecord {

	/** Id поста */
	const ATTR_POST_ID = 'post_id';

	/** Id тега */
	const ATTR_TAG_ID = 'tag_id';

	/** Индекс сортировки */
	const ATTR_SORT = 'sort';

	/**
	 * Привязать к посту блога теги
	 * @param BlogPost $post
	 * @throws ModelSaveException
	 */
	public static function bindPostTags(BlogPost $post) {
		$currentTagsModels = $post->tagsModels;

		//кладём строку с тегами в переменную для обработки
		$newTagsStr = $post->tags;

		//заменяем все запятые пробелами
		$newTagsStr = preg_replace('/,+/', ' ', $newTagsStr);
		//и если есть несколько пробелов подряд - то заменяем их одним пробелом
		$newTagsStr = preg_replace('/(\s{2,})/', ' ', $newTagsStr);

		//триммим
		$newTagsStr = trim($newTagsStr);

		//теперь разбиваем строку по пробелам на массив
		$newTagsArr = explode(' ', $newTagsStr);

		//ищем существующие теги в бд
		/** @var BlogTag[] $newTagsModels */
		$newTagsModels = BlogTag::find()
			->where((['name' => $newTagsArr]))
			->indexBy('name')
			->all();

		//флаг того, что теги в посте поменялись
		$changed = false;

		//сравниваем количество, которое найдено и которое есть в данный момент
		if (count($currentTagsModels) === count($newTagsArr)) {
			//если одинаковое, то сравниваем по содержимому
			$currentTagsArr = [];
			foreach($currentTagsModels as $currModel) {
				$currentTagsArr[] = $currModel->name;
			}

			if (!empty(array_diff($currentTagsArr, $newTagsArr)) || !empty(array_diff($newTagsArr, $currentTagsArr))) {
				//если теги поменялись
				$changed = true;
			}
		}
		else {
			//если не равно, то теги поменялись
			$changed = true;
		}

		if ($changed) {
			//если теги поменялись, то удаляем из БД все имеющиеся
			static::deleteAll([static::ATTR_POST_ID => $post->id]);

			//проходим по тегам, которые нужно добавить
			foreach($newTagsArr as $i => $newTagStr) {
				//создаём связь между постом и тегами
				$postTag = new BlogPostTag();
				$postTag->post_id = $post->id;

				//проверяем, есть ли уже текущий тег в базе,
				if (isset($newTagsModels[$newTagStr])) {
					//если есть, то берём его id
					$tagId = $newTagsModels[$newTagStr]->id;
				}
				else {
					//если нет, то создаём его
					$newTagModel = new BlogTag();
					$newTagModel->name = $newTagStr;
					if (!$newTagModel->save()) {
						throw new ModelSaveException('Не удалось сохранить новый тег: ' . $newTagStr . ', ошибки: ' . ErrorHelper::getErrorsString($newTagModel->getErrors()));
					}

					$newTagsModels[$newTagStr] = $newTagModel;

					$tagId = $newTagModel->id;
				}

				//добавляем id тега в связь
				$postTag->tag_id = $tagId;
				$postTag->sort = $i;

				if (!$postTag->save()) {
					throw new ModelSaveException('Не удалось сохранить новый тег: ' . $newTagStr . ', ошибки: ' . ErrorHelper::getErrorsString($postTag->getErrors()));
				}
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['post_id', 'tag_id'], 'required'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}

		if ($insert && $this->sort === null) {
			/** @var static $lastSort */
			$lastSort = static::find()
				->select('MAX(' . static::ATTR_SORT . ')')
				->where(['post_id' => $this->post_id])
				->column();

			if (!empty($lastSort)) {
				$this->sort = (int) $lastSort[0] + 1;
			}
			else {
				$this->sort = 0;
			}
		}

		return true;
	}

}