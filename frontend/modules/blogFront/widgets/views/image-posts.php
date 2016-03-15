<?php
use common\base\View;
use common\modules\image\models\ImageProvider;
use common\modules\image\models\Image;
use frontend\modules\blogFront\components\PostOutHelper;
use frontend\modules\blogFront\widgets\PostsWidget;
use yii\helpers\Html;

/** @var View $this */
/** @var PostsWidget $widget */
/** @var Image[] $images */
?>

<?php if (!empty($images)): ?>
	<div class="last-photos">
		<div class="photos-container">
			<?php foreach ($images as $image): ?>
				<div class="photo-item">
					<?= Html::a(
							Html::img(
									$image->getImageUrl(ImageProvider::FORMAT_THUMB),
									[
											'title' => PostOutHelper::clearString($image->post->title),
											'alt'   => PostOutHelper::clearString($image->post->title),
									]
							),
							['/blogFront/posts/view', 'title_url' => $image->post->title_url],
							['title' => PostOutHelper::clearString($image->post->title)]
					) ?>
				</div>
			<?php endforeach ?>
		</div>
	</div>
<?php endif ?>