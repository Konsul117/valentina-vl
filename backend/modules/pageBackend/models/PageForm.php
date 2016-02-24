<?php

namespace backend\modules\pageBackend\models;

use common\components\behaviors\ImageBindBehavior;
use common\components\behaviors\SeoTranslitBehavior;
use common\modules\page\models\Page;
use yii\helpers\ArrayHelper;

/**
 * Расширение модели "Страницы" для backend
 *
 * @inheritdoc
 */
class PageForm extends Page {

	/** Сценарий редактирования страницы из бэкэнда */
	const SCENARIO_UPDATE = 'scenarioUpdate';
	/** Поведение для связывания изображений */
	const BEHAVIOR_IMAGE_BIND = 'imageBind';

	public function scenarios() {
		return ArrayHelper::merge(parent::scenarios(), [
			static::SCENARIO_UPDATE => [
				static::ATTR_TITLE,
				static::ATTR_CONTENT,
				static::ATTR_IS_PUBLISHED,
			],
		]);
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
	public static function tableName() {
		return Page::tableName();
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return ArrayHelper::merge(
			parent::behaviors(),
			[
				'seo'                       => [
					'class'         => SeoTranslitBehavior::class,
					'attributeFrom' => static::ATTR_TITLE,
					'attributeTo'   => static::ATTR_TITLE_URL,
				],
				static::BEHAVIOR_IMAGE_BIND => [
					'class'         => ImageBindBehavior::class,
					'imagesField'   => static::REL_IMAGES,
					'contentFields' => [static::ATTR_CONTENT],
					'entityId'      => $this->getEntityId(),
				],
			]
		);
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

}