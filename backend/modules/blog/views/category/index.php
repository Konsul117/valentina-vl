<?php
use backend\modules\pageBackend\models\PageSearch;
use common\base\View;
use common\components\Formatter;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var View $this */
/** @var ActiveDataProvider $dataProvider */
/** @var PageSearch $searchModel */

?>

<h1>Категории</h1>

<?=
GridView::widget([
	'dataProvider'   => $dataProvider,
	'filterModel'    => $searchModel,
	'columns'        => [
		\common\modules\blog\models\BlogCategory::ATTR_ID,
		\common\modules\blog\models\BlogCategory::ATTR_TITLE,
		\common\modules\blog\models\BlogCategory::ATTR_TITLE_URL,
		\common\modules\blog\models\BlogCategory::ATTR_META_TITLE,
		\common\modules\blog\models\BlogCategory::ATTR_META_DESCRIPTION,
		[
			'class'    => ActionColumn::class,
			'template' => '{update}',
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
