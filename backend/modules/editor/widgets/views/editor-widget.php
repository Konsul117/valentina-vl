<?php
use backend\modules\editor\widgets\EditorWidget;
use yii\web\View;

/** @var View $this */
/** @var EditorWidget $widget */

?>

<?= $widget->form->field($widget->model, $widget->contentAttribute)->textarea(['id' => $widget->id]) ?>

<?php
$this->registerJs(
		'tinymce_init(\'' . $widget->id . '\''
		. ',\'' . $this->assetBundles[\backend\modules\editor\assets\TinyMCEInnerAsset::class]->baseUrl . '/css/editor_inner.css\')'); ?>
