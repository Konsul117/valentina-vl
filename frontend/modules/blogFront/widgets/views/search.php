<?php
use common\base\View;
use frontend\modules\blogFront\widgets\SearchWidget;
use yii\helpers\Html;

/** @var View $this */
/** @var SearchWidget $widget */
/** @var string $query */
?>

<div class="search-panel">
	<?= Html::beginForm(['/blogFront/posts/search'], 'get') ?>
	<?= Html::textInput('query', $query, [
			'maxlength'   => 50,
			'placeholder' => 'Поиск',
	]) ?>

	<?= Html::submitButton('', ['class' => 'icons icons-search']) ?>
	<?= Html::endForm() ?>
</div>
