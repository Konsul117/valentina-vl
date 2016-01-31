<?php
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
?>

<div class="blog-post-content">
	<?= PostOutHelper::wrapContentImages($post->content) ?>
</div>

<?= $commentWidget->run() ?>