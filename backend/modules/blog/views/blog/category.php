<?php
use backend\modules\blog\models\BlogPostSearch;
use common\components\Formatter;
use common\modules\blog\models\BlogCategory;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var BlogCategory $category */
/** @var ActiveDataProvider $dataProvider */
/** @var BlogPostSearch $searchModel */
?>

	<h1><?= $category->title ?></h1>

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
				'tags',
				'is_published',
				[
						'class'     => DataColumn::class,
						'attribute' => BlogPostSearch::ATTR_INSERT_STAMP,
						'format'    => 'localDateTime',
				],
				[
						'class'     => DataColumn::class,
						'attribute' => BlogPostSearch::ATTR_UPDATE_STAMP,
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