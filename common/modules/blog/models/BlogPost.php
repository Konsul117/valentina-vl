<?php

namespace common\modules\blog\models;

use common\components\TimestampUTCBehavior;
use yii\db\ActiveRecord;

/**
 * Посты блога
 *
 * @property int    $id              Уникальный идентификатор поста
 * @property int    $category_id     Категория
 * @property string $title           Заголовок поста
 * @property string $title_url       Заголовок ЧПУ поста
 * @property string $content         Контент
 * @property string $tags            Теги
 * @property bool   $is_published    Состояние опубликованности
 * @property string $insert_stamp    Дата-время создания поста
 * @property string $update_stamp    Дата-время обновления поста
 */
class BlogPost extends ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampUTCBehavior::className(),
				'createdAtAttribute' => 'insert_stamp',
				'updatedAtAttribute' => 'update_stamp',
			],
		];
	}

}