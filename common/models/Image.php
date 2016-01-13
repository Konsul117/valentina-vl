<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Изображения
 *
 * @property int $id                         Уникальынй идентификатор изображения
 * @property int $related_entity_id          Идентификатр сущности, с которой связано изображение
 * @property int $related_entity_item_id     Идентификатр объекта сущности, с которой связано изображение
 */
class Image extends ActiveRecord {

}