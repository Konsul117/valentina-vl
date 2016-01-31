<?php
use backend\modules\pageBackend\models\PageSearch;
use common\base\View;
use common\components\Formatter;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var PageSearch $searchModel */

?>

<h1>Страницы</h1>

<p>
	<?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?=
GridView::widget([
	'dataProvider'   => $dataProvider,
	'filterModel'    => $searchModel,
	'columns'        => [
		'id',
		'title',
		'is_published',
		[
			'class'     => DataColumn::class,
			'attribute' => PageSearch::ATTR_INSERT_STAMP,
			'format'    => 'localDateTime',
		],
		[
			'class'     => DataColumn::class,
			'attribute' => PageSearch::ATTR_UPDATE_STAMP,
			'format'    => 'localDateTime',
		],
		[
			'class'    => ActionColumn::class,
			'template' => '{view} {update} {delete}',
		],
	],
	'layout'         => "{pager}\n{summary}\n{items}\n{pager}",
	'formatter'      => [
		'class'      => Formatter::class,
		'dateFormat' => 'd.m.Y H:i',
	],
	'filterSelector' => 'select[name="per-page"]',
])
?>
