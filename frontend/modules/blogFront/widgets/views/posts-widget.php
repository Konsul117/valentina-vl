<?php
use common\components\Formatter;
use common\modules\blog\models\BlogPost;
use frontend\modules\blogFront\widgets\PostsWidget;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var PostsWidget $widget */
/** @var BlogPost[] $posts */
/** @var Pagination $pages */
$formatter = new Formatter();
?>


<?php if (!empty($posts)): ?>
	<div class="blog-posts">
		<?php foreach ($posts as $post): ?>
			<div class="post-item">
				<h2><?= Html::a($post->title, ['/blogFront/posts/view', 'title_url' => $post->title_url]) ?></h2>

				<div class="post-stamp">
					<?= $formatter->asLocalDateTime($post->insert_stamp, 'd.m.Y H:i') ?>
				</div>

				<div class="item-content">
					<?= $post->short_content ?>
				</div>

				<p>
					<?= Html::a('Подробнее', ['/blogFront/posts/view', 'title_url' => $post->title_url], [
						'class' => 'btn btn-default'
					]) ?>
				</p>
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