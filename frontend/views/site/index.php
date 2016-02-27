<?php

use common\base\View;
use common\modules\image\models\ImageProvider;
use common\modules\page\models\Page;
use frontend\modules\blogFront\components\PostOutHelper;
use yii\base\Widget;

/** @var $this View */
/** @var Widget $postsWidget */
/** @var Page $mainPage */

$this->title = 'Главная';
?>

<?php if ($mainPage !== null): ?>
	<?= PostOutHelper::wrapContentImages($mainPage->content, [ImageProvider::FORMAT_MEDIUM]) ?>
<?php endif ?>

<?php if ($postsWidget !== null): ?>
	<?= $postsWidget->run() ?>
<?php endif ?>
