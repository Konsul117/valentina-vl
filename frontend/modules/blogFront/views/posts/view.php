<?php
use common\modules\blog\models\BlogPost;
use common\base\View;

/** @var View $this */
/** @var BlogPost $post */

$this->title = $post->title;

$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/index'], 'Блог');
$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/category', 'category_url' => $post->category->title_url],
		$post->category->title);
$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/view', 'title_url' => $post->title_url], $post->title);
?>

<div class="blog-post-content">
	<?= $post->content ?>
</div>


