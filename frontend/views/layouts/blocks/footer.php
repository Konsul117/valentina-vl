<?php
use frontend\modules\blogFront\BlogFront;
use yii\helpers\Html;

/** @var BlogFront $blogFrontModule */
$blogFrontModule = Yii::$app->modules['blogFront'];
?>

<footer class="footer">

	<?= $blogFrontModule->getTagsCloudWidget()->run() ?>
	<div class="sub-line">
		<div class="left">
			<p>Vladivostok <?= date('Y') ?></p>
		</div>

		<div class="right">
			<ul class="social-icons list-unstyled">
				<li class="social-logo social-logo-instagram"><?= Html::a('', 'https://www.instagram.com/vasa_vasa400/ ', ['target' => '_blank']) ?></li>
				<li class="social-logo social-logo-ok"><?= Html::a('', 'http://ok.ru/valentina.panchenko1 ', ['target' => '_blank']) ?></li>
				<li class="social-logo social-logo-vk"><?= Html::a('', 'https://vk.com/id215242627 ', ['target' => '_blank']) ?></li>
			</ul>
		</div>
	</div>
</footer>
