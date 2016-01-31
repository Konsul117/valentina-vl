<?php
use common\base\View;
use common\modules\page\models\Page;
use frontend\modules\blogFront\components\PostOutHelper;

/** @var View $this */
/** @var Page $page */

$this->breadcrumbs->addBreadcrumb(['/pageFront/page/view', 'title_url' => $page->title_url],
	$page->title);
?>

<?= PostOutHelper::wrapContentImages($page->content) ?>
