<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('tests', dirname(dirname(__DIR__)) . '/tests');
Yii::setAlias('upload_images_path', Yii::getAlias('@common/runtime/upload'));
Yii::setAlias('resized_images_path', Yii::getAlias('@frontend/web/upload/images/resized'));
Yii::setAlias('resized_images_url', Yii::getAlias('upload/images/resized'));
