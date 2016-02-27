<?php
use frontend\modules\blogFront\BlogFront;
use frontend\modules\pageFront\PageFront;
use yii\helpers\Html;

?>

<header>

	<?php
	/** @var BlogFront $blogFrontModule */
	$blogFrontModule = Yii::$app->modules['blogFront'];
	?>

	<div class="header-sup">
		<div class="inner">

		</div>
	</div>

	<?= $blogFrontModule->getImagePostsWidget(10)->run() ?>

	<div class="img-name-wrapper">
		<div class="img-name-container">
			<div class="img-name img-name-desktop">
				<?= Html::a('', ['/']) ?>
			</div>

			<div class="img-name img-name-mobile">
				<?= Html::a('', ['/']) ?>
				<div class="wrapper">
					<div class="img-part left-part"></div>
					<div class="img-part right-part"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="sup-menu">

	</div>

	<div class="menu-wrapper">

		<div class="social-icons">
			<div class="social-logo social-logo-instagram"><?= Html::a('', 'https://www.instagram.com/vasa_vasa400/ ',
						['target' => '_blank']) ?></div><!--
		 -->
			<div class="social-logo social-logo-ok"><?= Html::a('', 'http://ok.ru/valentina.panchenko1 ',
						['target' => '_blank']) ?></div><!--
		 -->
			<div class="social-logo social-logo-vk"><?= Html::a('', 'https://vk.com/id215242627 ',
						['target' => '_blank']) ?></div>
		</div>

		<div class="menu">
			<ul class="menu-items">
				<li class="item-biser">
					<?= Html::a('', ['/blogFront/posts/category', 'category_url' => 'biser']) ?>
				</li>

				<li class="item-not-biser">
					<?= Html::a('', ['/blogFront/posts/category', 'category_url' => 'not_biser']) ?>
				</li>

				<?php
				$contactsTitleUrl = false;
				if (isset(Yii::$app->modules['pageFront'])) {
					/** @var PageFront $pageModule */
					$pageModule       = Yii::$app->modules['pageFront'];
					$contactsTitleUrl = $pageModule->getPageUrlById(\common\modules\page\models\Page::PAGE_ID_CONTANCTS);
				}
				?>

				<?php if ($contactsTitleUrl): ?>
					<li class="item-contacts">
						<?= Html::a('', ['/pageFront/page/view', 'title_url' => $contactsTitleUrl]) ?>
					</li>
				<?php endif ?>
			</ul>
		</div>

		<?= $blogFrontModule->getSearchWidget()->run() ?>
	</div>

</header>