<?php

namespace frontend\modules\blogFront\widgets;

use common\modules\image\models\Image;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\Query;

/**
 * Виджет линейки главных изображений постов
 */
class ImagePostsWidget extends Widget {

	/**
	 * @var Query
	 */
	public $query;

	/**
	 * Количество фото
	 *
	 * @var int
	 */
	public $limit;

	public function run() {
		if (!$this->query instanceof Query) {
			throw new InvalidConfigException('Отсуствует query');
		}

		if ($this->limit === null) {
			throw new InvalidConfigException('Не указан лимит вывода изображений');
		}

		/** @var Image[] $images */
		$images = $this->query
			->limit($this->limit)
			->all();

		return $this->render('image-posts', [
			'widget' => $this,
			'images' => $images,
		]);
	}

}