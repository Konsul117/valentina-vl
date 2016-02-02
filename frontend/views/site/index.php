<?php

use common\modules\image\models\ImageProvider;
use common\modules\page\models\Page;
use frontend\modules\blogFront\components\PostOutHelper;
use yii\base\Widget;

/* @var $this \common\base\View */
/** @var Widget $postsWidget */
/** @var Page $mainPage */

$this->title = 'Валентина Панченко';
$this->titleCustom = 'Главная';
?>

<?php if ($mainPage !== null): ?>
	<?= PostOutHelper::wrapContentImages($mainPage->content, [ImageProvider::FORMAT_MEDIUM]) ?>
<?php endif ?>

<?php if ($postsWidget !== null): ?>
	<?= $postsWidget->run() ?>
<?php endif ?>
