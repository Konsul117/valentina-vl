<?php
use common\modules\blog\models\BlogCategory;
use yii\base\Widget;
use common\base\View;

/** @var View $this */
/** @var Widget $postsWidget */
/** @var BlogCategory $category */

$this->title = $category->title;

$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/category', 'category_url' => $category->title_url], $category->title);

$this->metaTagContainer->title       = $category->meta_title;
$this->metaTagContainer->description = $category->meta_description;
?>


<?= $postsWidget->run() ?>