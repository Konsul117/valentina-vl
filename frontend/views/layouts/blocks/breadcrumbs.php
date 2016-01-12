<?php
/**
 * @var $this \common\base\View
 */

use yii\helpers\Html;

$this->breadcrumbs = \yii\helpers\ArrayHelper::merge(['/' => 'Главная'], $this->breadcrumbs);
$count             = count($this->breadcrumbs);
?>

<?php if ($count > 1): ?>
	<ul class="breadcrubms">
		<?php $i = 0 ?>
		<?php foreach ($this->breadcrumbs as $route => $title): ?>
			<li>
				<?= Html::a($title, [$route]) ?>
				<?php if (++$i < ($count)): ?><span class="delimiter"> > </span><?php endif ?>
			</li>
		<? endforeach ?>
	</ul>
<?php endif ?>