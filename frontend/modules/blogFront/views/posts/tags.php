<?php
use common\modules\blog\models\BlogTag;
use common\base\View;
use yii\bootstrap\Html;

/** @var View $this */
/** @var BlogTag[] $tags */

$this->breadcrumbs->addBreadcrumb(['/blogFront/tag'], 'Теги');
?>

<?php if (!empty($tags)): ?>
	<ul class="list-unstyled tags-list">
	<?php foreach($tags as $tag): ?>
		<li><?= Html::a($tag->name, ['/blogFront/posts/tag', 'tag' => $tag->name_url]) ?></li>
	<?php endforeach ?>
	</ul>
<?php else: ?>
	Теги не найдены
<?php endif ?>