<?php
/**
 * @var $this \common\base\View
 */

use yii\helpers\Html;

$count = count($this->breadcrumbs);
?>

<?php if ($count > 1): ?>
	<ul class="breadcrubms">
		<?php $i = 0 ?>
		<?php foreach ($this->breadcrumbs as $crumb): ?>
			<li>
				<?php if (++$i < ($count)): ?>
					<?= Html::a($crumb->title, $crumb->url) ?>
					<span class="delimiter"> > </span>
				<?php else: ?>
					<?= $crumb->title ?>
				<?php endif ?>
			</li>
		<? endforeach ?>
	</ul>
<?php endif ?>