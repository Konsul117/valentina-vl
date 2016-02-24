<?php

namespace backend\modules\blog\models;

use common\components\behaviors\ImageBindBehavior;
use common\components\behaviors\SeoTranslitBehavior;
use common\exceptions\ModelSaveException;
use common\modules\blog\models\BlogPost;
use common\modules\blog\models\BlogPostTag;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * Модель формы поста, расширяющая модель поста
 *
 * @inheritdoc
 */
class BlogPostForm extends BlogPost {

	/** Сценарий сохранения */
	const SCENARIO_UPDATE = 'scenarioUpdate';

	/** Поведение для связывания изображений */
	const BEHAVIOR_IMAGE_BIND = 'imageBind';

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return BlogPost::tableName();
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return ArrayHelper::merge(
			parent::rules(),
			[
				[static::ATTR_TITLE, 'unique'],
			]
		);
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
				static::BEHAVIOR_IMAGE_BIND => [
					'class'         => ImageBindBehavior::class,
					'imagesField'   => static::REL_IMAGES,
					'contentFields' => [
						static::ATTR_SHORT_CONTENT,
						static::ATTR_CONTENT,
					],
					'entityId'      => $this->getEntityId(),
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
	}

	/**
	 * Сохранить пост с новыми изображениями
	 *
	 * @param int[] $imagesIds Массив id изображений
	 *
	 * @return bool
	 */
	public function saveWithNewImages($imagesIds) {
		/** @var ImageBindBehavior $imageBehavior */
		$imageBehavior = $this->getBehavior(static::BEHAVIOR_IMAGE_BIND);
		$this->on(static::EVENT_AFTER_INSERT, function () use ($imagesIds, $imageBehavior) {
			$imageBehavior->bindImagesToPost($this, $imagesIds);
		}, null, false);

		$this->on(static::EVENT_AFTER_UPDATE, function () use ($imagesIds, $imageBehavior) {
			$imageBehavior->bindImagesToPost($this, $imagesIds);
		}, null, false);

		return $this->save();
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
	}

}