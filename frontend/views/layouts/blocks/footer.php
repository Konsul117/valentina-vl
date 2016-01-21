<?php
use frontend\modules\blogFront\BlogFront;

/** @var BlogFront $blogFrontModule */
$blogFrontModule = Yii::$app->modules['blogFront'];
?>

<footer class="footer">


	<div class="container">
		<?= $blogFrontModule->getTagsCloudWidget()->run() ?>
		<p>Vladivostok <?= date('Y') ?></p>
	</div>
</footer>
