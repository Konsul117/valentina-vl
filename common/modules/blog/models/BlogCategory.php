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

}