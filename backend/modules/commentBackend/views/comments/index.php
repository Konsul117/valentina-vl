<?php
use backend\modules\commentBackend\models\CommentSearch;
use common\base\View;
use common\components\Formatter;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;
use yii\grid\GridView;

/** @var ActiveDataProvider $dataProvider */
/** @var CommentSearch $searchModel */
/** @var View $this */
?>

<h1>Комментарии</h1>

<?=
GridView::widget([
	'dataProvider'   => $dataProvider,
	'filterModel'    => $searchModel,
	'columns'        => [
		CommentSearch::ATTR_ID,
		CommentSearch::ENTITY_ITEM_TITLE,
		CommentSearch::ATTR_NICKNAME,
		CommentSearch::ATTR_CONTENT,
		CommentSearch::ATTR_IS_PUBLISHED,
		[
			'class'     => DataColumn::class,
			'attribute' => CommentSearch::ATTR_INSERT_STAMP,
			'format'    => 'localDateTime',
		],
		[
			'class'     => DataColumn::class,
			'attribute' => CommentSearch::ATTR_UPDATE_STAMP,
			'format'    => 'localDateTime',
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
