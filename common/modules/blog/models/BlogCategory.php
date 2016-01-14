<?php

namespace common\modules\blog\models;

use yii\db\ActiveRecord;

/**
 * Категории блога
 *
 * @property int    $id           Уникальный идентификатор категории
 * @property string $title        Название категории
 * @property string $title_url    Название ЧПУ категории
 */
class BlogCategory extends ActiveRecord {
	/** Уникальный идентификатор категории */
	const ATTR_ID = 'id';

	/** Название категории */
	const ATTR_TITLE = 'title';

	/** Название ЧПУ категории */
	const ATTR_TITLE_URL = 'title_url';

}