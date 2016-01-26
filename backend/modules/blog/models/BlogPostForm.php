<?php


namespace backend\modules\blog\models;


use common\components\SeoTranslitBehavior;
use common\exceptions\ModelSaveException;
use common\models\Image;
use common\modules\blog\models\BlogPost;
use common\modules\blog\models\BlogPostTag;
use phpQuery;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class BlogPostForm extends BlogPost {

	const SCENARIO_UPDATE = 'scenarioUpdate';

	public static function tableName() {
		return BlogPost::tableName();
	}

	public function behaviors() {
		return ArrayHelper::merge(
			parent::behaviors(),
			[
				[
					'class'         => SeoTranslitBehavior::class,
					'attributeFrom' => static::ATTR_TITLE,
					'attributeTo'   => static::ATTR_TITLE_URL,
				],
			]
		);
	}

	public function scenarios() {
		return [
			static::SCENARIO_UPDATE => [
				static::ATTR_TITLE,
				static::ATTR_SHORT_CONTENT,
				static::ATTR_CONTENT,
				static::ATTR_TAGS,
				static::ATTR_IS_PUBLISHED,
			],
		];
	}

	public function init() {
		parent::init();

		$this->on(static::EVENT_AFTER_INSERT, [$this, 'relatedSaveActions']);
		$this->on(static::EVENT_AFTER_UPDATE, [$this, 'relatedSaveActions']);
		$this->on(static::EVENT_AFTER_DELETE, [$this, 'relatedDeleteActions']);
	}

	public function relatedSaveActions() {
		try {
			BlogPostTag::bindPostTags($this);
		}
		catch (ModelSaveException $e) {
			throw new Exception('Исключение при сохранении тегов поста: ' . $e->getMessage(), 0, $e);
		}

		$this->updateMainImage();
	}

	/**
	 * Обновление главного изображения поста
	 */
	protected function updateMainImage() {
		$images = $this->images;

		if (empty($images)) {
			return ;
		}

		$doc = phpQuery::newDocumentHTML($this->content);

		$imagesIds = [];

		foreach ($doc->find('img[data-image-id]') as $imgEl) {
			$imageId = (int) $imgEl->getAttribute('data-image-id');
			if (!$imageId) {
				continue;
			}

			$imagesIds[] = $imageId;
		}

		foreach($images as $image) {
			//удаляем те картинки, которых нет в посте
			if (!in_array($image->id, $imagesIds)) {
				$image->delete();
			}
		}

		if (empty($imagesIds)) {
			return ;
		}

		reset($imagesIds);

		$firstImageId = current($imagesIds);

		if (isset($images[$firstImageId])) {
			$id = $this->id;
			$command = Yii::$app->db->createCommand('UPDATE ' . Image::tableName()
				. ' SET ' . Image::ATTR_IS_MAIN . ' = 0'
				. ' WHERE ' . Image::ATTR_RELATED_ENTITY_ITEM_ID . ' = :post_id')
				->bindParam(':post_id', $id);

			$command->execute();
			$images[$firstImageId]->is_main = true;
			$images[$firstImageId]->save();
		}
	}

	public function relatedDeleteActions() {
		//удаляем связанные картинки
		foreach ($this->images as $image) {
			$image->delete();
		}

		//удаляем связи с тегами
		$postRelations = BlogPostTag::findAll([BlogPostTag::ATTR_POST_ID => $this->id]);

		foreach($postRelations as $rel) {
			$rel->delete();
		}
	}

}