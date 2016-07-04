<?php
use common\base\View;
use common\modules\image\models\ImageProvider;
use common\modules\page\models\Page;
use frontend\modules\blogFront\components\PostOutHelper;

/** @var View $this */
/** @var Page $page */

$this->breadcrumbs->addBreadcrumb(['/pageFront/page/view', 'title_url' => $page->title_url], $page->title);

$this->metaTagContainer->title = $page->title;

$mainImage = $page->mainImage;

$mainImageTitle = null;

if ($mainImage !== null) {
	$this->metaTagContainer->image = $mainImage->getImageUrl(ImageProvider::FORMAT_MEDIUM);
	$mainImageTitle = $mainImage->title;
}
?>

<?= PostOutHelper::wrapContentImages($page->content, [ImageProvider::FORMAT_MEDIUM], $mainImageTitle) ?>