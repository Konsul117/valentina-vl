<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('tests', dirname(dirname(__DIR__)) . '/tests');
Yii::setAlias('root', dirname(dirname(__DIR__)));

//путь для загрузки оригиниалов изображений
Yii::setAlias('upload_watermark_path', Yii::getAlias('@root/upload/watermark'));
Yii::setAlias('upload_images_path', Yii::getAlias('@root/upload/images'));
//путь для сохранений тамбов (должен быть доступен извне)
Yii::setAlias('resized_images_path', Yii::getAlias('@frontend/web/upload/images/resized'));
//URL для загрузки тамбов
Yii::setAlias('resized_images_url', Yii::getAlias('upload/images/resized'));
