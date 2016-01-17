<?php
use common\modules\blog\models\BlogPost;
use yii\base\View;

/** @var View $this */
/** @var BlogPost $post */
?>

<h1><?= $post->title ?></h1>

<div class="blog-post-content">
	<?= $post->content ?>
</div>


