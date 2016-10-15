<?php
use common\base\View;
use frontend\modules\blogFront\widgets\SearchWidget;
use yii\helpers\Html;

/**
 * @var View         $this
 * @var SearchWidget $widget Виджет
 */
?>

	<?= Html::beginForm(['/blogFront/posts/search'], 'get') ?>
	<?= Html::textInput('query', $widget->query, [
			'maxlength'   => 50,
			'placeholder' => 'Поиск',
	]) ?>

	<button type="submit" class="<?= $widget->buttonClass ?>">
		<span class="button-label">
			Найти
		</span>
	</button>
	<?= Html::endForm() ?>
