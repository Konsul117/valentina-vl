<?php
use common\base\View;
use frontend\modules\blogFront\widgets\TagsPostWidget;
use yii\helpers\Html;

/** @var View $this */
/** @var TagsPostWidget $widget */
?>

<?php if (!empty($widget->post->tagsModels)): ?>

	<div class="tags-post">
		Теги:
		<ul class="list-unstyled tags-list">
			<?php foreach ($widget->post->tagsModels as $tag): ?>
				<li><?= Html::a($tag->name, ['/blogFront/posts/tag', 'tag' => $tag->name_url]) ?></li>
			<?php endforeach ?>
		</ul>
	</div>

<?php endif ?>