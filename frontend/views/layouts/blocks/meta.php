<?php
use common\base\View;
use common\components\MetaTagContainer;

/** @var View $this */
/** @var MetaTagContainer $metaTagContainer */
?>

<?php if ($metaTagContainer->title): ?>
	<meta name="title" content="<?= $metaTagContainer->title ?>">
	<meta property="og:title" content="<?= $metaTagContainer->title ?>" />
<?php endif ?>

<?php if ($metaTagContainer->description): ?>
	<meta name="description" content="<?= $metaTagContainer->description ?>">
	<meta property="og:description" content="<?= $metaTagContainer->description ?>" />
<?php endif ?>

<?php if ($metaTagContainer->image): ?>
	<meta property="og:image" content="<?= $metaTagContainer->image ?>">
<?php endif ?>
