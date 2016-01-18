<?php
use common\modules\blog\models\BlogPost;
use frontend\modules\blogFront\widgets\PostsWidget;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var PostsWidget $widget */
/** @var BlogPost[] $posts */
/** @var Pagination $pages */
?>


<?php if (!empty($posts)): ?>
	<div class="blog-posts">
		<?php foreach ($posts as $post): ?>
			<div class="post-item">
				<h2><?= Html::a($post->title, ['/blogFront/posts/view', 'title_url' => $post->title_url]) ?></h2>

				<div class="item-content">
					<?= $post->short_content ?>
				</div>
			</div>
		<?php endforeach ?>

		<?= LinkPager::widget([
				'pagination' => $pages,
		]) ?>
	</div>
<?php elseif ($widget->showEmptyLabel): ?>
	<div class="alert alert-info">
		По вашему запросу ничего не найдено.
	</div>
<?php endif ?>