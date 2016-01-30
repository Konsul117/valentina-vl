<?php


namespace backend\modules\blog\models;


use common\components\ErrorHelper;
use common\components\SeoTranslitBehavior;
use common\exceptions\ModelSaveException;
use common\models\Entity;
use common\models\Image;
use common\modules\blog\models\BlogPost;
use common\modules\blog\models\BlogPostTag;
use phpQuery;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * Модель формы поста, расширяющая модель поста
 * @inheritdoc
 */
class BlogPostForm extends BlogPost {

	/** Сценарий сохранения */
	const SCENARIO_UPDATE = 'scenarioUpdate';

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return BlogPost::tableName();
	}

	/**
	 * @inheritdoc
	 */
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

	/**
	 * @inheritdoc
	 */
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

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->on(static::EVENT_AFTER_INSERT, [$this, 'relatedSaveActions']);
		$this->on(static::EVENT_AFTER_UPDATE, [$this, 'relatedSaveActions']);
		$this->on(static::EVENT_AFTER_DELETE, [$this, 'relatedDeleteActions']);
	}

	/**
	 * Сохранить пост с новыми изображениями
	 *
	 * @param int[] $imagesIds Массив id изображений
	 *
	 * @return bool
	 */
	public function saveWithNewImages($imagesIds) {
		$this->on(static::EVENT_AFTER_INSERT, function() use ($imagesIds) {
			$this->bindImagesToPost($imagesIds);
		}, null, false);

		$this->on(static::EVENT_AFTER_UPDATE, function() use ($imagesIds) {
			$this->bindImagesToPost($imagesIds);
		}, null, false);

		return $this->save();
	}

	/**
	 * Привязать изображения к сущности
	 *
	 * @param array $imagesIds       массив id изображений для привязки
	 *
	 * @return bool
	 *
	 * @throws ModelSaveException
	 */
	public function bindImagesToPost(array $imagesIds) {
		if ($this->isNewRecord) {
			throw new ModelSaveException('Невозможно привязать изображения: пост не сохранён');
		}

		if (empty($imagesIds)) {
			return false;
		}

		/** @var Image[] $imagesModels */
		$imagesModels = Image::findAll($imagesIds);

		if (empty($imagesModels)) {
			return false;
		}

		foreach ($imagesModels as $imagesModel) {
			if ($imagesModel->related_entity_item_id === null) {
				$imagesModel->related_entity_id      = Entity::ENTITY_BLOG_POST_ID;
				$imagesModel->related_entity_item_id = $this->id;

				$saveResult = $imagesModel->save();

				if ($saveResult === false) {
					$errors = $imagesModel->getErrors();

					if (!empty($errors)) {
						throw new ModelSaveException('Ошибки при сохранении модели Image: ' . ErrorHelper::getErrorsString($errors));
					}
					else {
						throw new ModelSaveException('Неизвестная ошибка при сохранении модели');
					}
				}
			}
		}

		return true;
	}

	/**
	 * Действия над связанными сущностями при сохранении модели (вставка, обновление)
	 * @throws Exception
	 */
	public function relatedSaveActions() {
		//присоединяем теги
		try {
			BlogPostTag::bindPostTags($this);
		}
		catch (ModelSaveException $e) {
			throw new Exception('Исключение при сохранении тегов поста: ' . $e->getMessage(), 0, $e);
		}

		//обновляем главное изображение поста
		$this->actualizePostImages();
	}

	/**
	 * Актуализация изображений по картинкам, которые помещены в тело поста
	 * Обновление главного изображения поста
	 */
	protected function actualizePostImages() {
		//получаем присоединённые к посту изображения
		$images = $this->images;

		if (empty($images)) {
			return ;
		}

		//парсим id изображений в теле поста
		$doc = phpQuery::newDocumentHTML($this->content);

		//массив спарсенных id изображений
		$imagesIds = [];

		foreach ($doc->find('img[data-image-id]') as $imgEl) {
			$imageId = (int) $imgEl->getAttribute('data-image-id');
			if (!$imageId) {
				continue;
			}

			$imagesIds[] = $imageId;
		}

		//проходим по привязанным изображениям
		foreach($images as $image) {
			//удаляем те картинки, которых нет в посте
			if (!in_array($image->id, $imagesIds)) {
				$image->delete();
			}
		}

		$this->refresh();

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

	/**
	 * Действия над связанными сущностями при удалении модели
	 * @throws \Exception
	 */
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