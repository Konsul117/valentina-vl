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

		$newTagsStr = explode(' ', $post->tags);

		$newTagsStr = array_filter($newTagsStr, function($item) {
			return $item !== '';
		});

		/** @var BlogTag[] $newTagsModels */
		$newTagsModels = BlogTag::find()
			->where((['name' => $newTagsStr]))
			->indexBy('name')
			->all();

		$changed = false;

		if (count($currentTagsModels) === count($newTagsStr)) {
			$currentTagsStr = [];
			foreach($currentTagsModels as $currModel) {
				$currentTagsStr[] = $currModel->name;
			}

			if (!empty(array_diff($currentTagsStr, $newTagsStr)) || !empty(array_diff($newTagsStr, $currentTagsStr))) {
				$changed = true;
			}
		}
		else {
			$changed = true;
		}

		if ($changed) {
			static::deleteAll([static::ATTR_POST_ID => $post->id]);

			foreach($newTagsStr as $i => $newTagStr) {
				$postTag = new BlogPostTag();
				$postTag->post_id = $post->id;

				if (isset($newTagsModels[$newTagStr])) {
					$tagId = $newTagsModels[$newTagStr]->id;
				}
				else {
					$newTagModel = new BlogTag();
					$newTagModel->name = $newTagStr;
					if (!$newTagModel->save()) {
						throw new ModelSaveException('Не удалось сохранить новый тег: ' . $newTagStr . ', ошибки: ' . ErrorHelper::getErrorsString($newTagModel->getErrors()));
					}

					$newTagsModels[$newTagStr] = $newTagModel;

					$tagId = $newTagModel->id;
				}

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