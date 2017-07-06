<?php
use common\base\View;
use common\components\Formatter;
use common\modules\blog\models\BlogPost;
use common\modules\image\models\ImageProvider;
use frontend\modules\blogFront\components\PostOutHelper;
use frontend\modules\blogFront\widgets\TagsPostWidget;
use yii\base\Widget;

/** @var View $this */
/** @var BlogPost $post */
/** @var Widget $commentWidget */

$this->title = $post->title;

$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/category', 'category_url' => $post->category->title_url],
		$post->category->title);
$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/view', 'title_url' => $post->title_url], $post->title);

$this->metaTagContainer->title = PostOutHelper::clearString($post->title);
$this->metaTagContainer->description = PostOutHelper::clearString($post->short_content);

$mainImage = $post->mainImage;

if ($mainImage !== null) {
	$this->metaTagContainer->image = $mainImage->getImageUrl(ImageProvider::FORMAT_MEDIUM);
}

$formatter = new Formatter();
?>

<div class="blog-post-content clearfix">

	<div class="post-stamp">
		<?= $formatter->asLocalDateTime($post->insert_stamp, 'd.m.Y H:i') ?>
	</div>

	<?= PostOutHelper::wrapContentImages($post->content, ImageProvider::FORMAT_MEDIUM, $post->title) ?>
</div>

<?= TagsPostWidget::widget(['post' => $post]) ?>

<?= $this->render('//blocks/socials') ?>

<?#= $commentWidget->run() ?>