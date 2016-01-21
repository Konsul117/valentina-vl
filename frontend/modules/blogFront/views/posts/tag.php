<?php
use common\modules\blog\models\BlogTag;
use yii\base\Widget;
use common\base\View;

/** @var View $this */
/** @var Widget $postsWidget */
/** @var BlogTag $tag */

$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/tags'], 'Теги');
$this->breadcrumbs->addBreadcrumb(['/blogFront/posts/tag', 'tag' => $tag->name_url], 'Тег ' . $tag->name);
?>

<?= $postsWidget->run() ?>