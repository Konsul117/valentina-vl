<?php
use common\base\View;
use common\interfaces\ImageProvider;
use common\models\Image;
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
							Html::img($image->getImageUrl(ImageProvider::FORMAT_THUMB)),
							['/blogFront/posts/view', 'title_url' => $image->post->title_url]
					) ?>
				</div>
			<?php endforeach ?>
		</div>
	</div>
<?php endif ?>