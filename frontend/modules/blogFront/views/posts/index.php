<?php
use yii\base\Widget;
use common\base\View;

/** @var View $this */
/** @var Widget $postsWidget */

$this->breadcrumbs['blog'] =  'Блог';
?>


<?= $postsWidget->run() ?>