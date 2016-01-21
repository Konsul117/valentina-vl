<?php
use yii\base\Widget;
use common\base\View;

/** @var View $this */
/** @var Widget $postsWidget */

$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/search'], 'Поиск');
?>

<?= $postsWidget->run() ?>