<?php
use common\modules\image\models\ImageProvider;
use common\modules\blog\models\BlogPost;
use common\base\View;
use frontend\modules\blogFront\components\PostOutHelper;
use yii\base\Widget;

/** @var View $this */
/** @var BlogPost $post */
/** @var Widget $commentWidget */

$this->title = $post->title;

$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/category', 'category_url' => $post->category->title_url],
		$post->category->title);
$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/view', 'title_url' => $post->title_url], $post->title);

$this->metaTagContainer->title = $post->title;
$this->metaTagContainer->description = PostOutHelper::clearString($post->short_content);

$mainImage = $post->mainImage;

if ($mainImage !== null) {
	$this->metaTagContainer->image = $mainImage->getImageUrl(ImageProvider::FORMAT_MEDIUM);
}
?>

<div class="blog-post-content">
	<?= PostOutHelper::wrapContentImages($post->content, [ImageProvider::FORMAT_MEDIUM]) ?>
</div>

<?= $this->render('//blocks/socials') ?>

<?= $commentWidget->run() ?>