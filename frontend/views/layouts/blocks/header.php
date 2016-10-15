<?php
use common\base\View;
use common\modules\page\models\Page;
use Faker\Provider\Uuid;
use frontend\modules\blogFront\BlogFront;
use frontend\modules\pageFront\PageFront;
use yii\helpers\Url;
use yii\widgets\Spaceless;

/**
 * @var View $this
 */
?>

<header>

	<?php
	/** @var BlogFront $blogFrontModule */
	$blogFrontModule = Yii::$app->modules['blogFront'];
	?>

	<?= $blogFrontModule->getImagePostsWidget(10)->run() ?>

	<?php Spaceless::begin() ?>
	<div class="img-name-wrapper">
		<div class="img-name-container">
			<a class="img-name" href="<?= Url::to(['/']) ?>" alt="Авторский блог Валентины Панченко" title="Авторский блог Валентины Панченко"></a>
			<a class="beads-photo" href="<?= Url::to(['/']) ?>" alt="Авторский блог Валентины Панченко" title="Авторский блог Валентины Панченко"></a>

				<div class="search-block">
					<?= $blogFrontModule->getSearchWidget()->run() ?>
				</div>


				<?php $searchPanelGuid = Uuid::uuid(); ?>
				<div class="search-heading" id="<?= $searchPanelGuid ?>">
					<div class="search-panel hidden" data-role="search-panel">
						<?= $blogFrontModule->getSearchWidget('btn btn-panel-search')->run() ?>
					</div>
					<button class="btn-header-search glyphicon glyphicon-search" data-role="search-panel-open"></button>
				</div>

				<?php $this->registerJs('$("#' . $searchPanelGuid . '").searchHeadingPanel();') ?>
		</div>


	</div>
	<?php Spaceless::end() ?>

	<?php Spaceless::begin() ?>
		<div class="menu-wrapper">

			<ul class="menu">
				<?php
				/** @var PageFront $pageModule */
				$pageModule       = Yii::$app->modules['pageFront'];
				?>

				<li class="menu-about-me">
					<a href="<?= Url::to(['/pageFront/page/view', 'title_url' => $pageModule->getPageUrlById(Page::PAGE_ID_MAIN)]) ?>">
						<span class="text-label">Обо мне</span>
					</a>
				</li>

				<li class="menu-biser">
					<a href="<?= Url::to(['/blogFront/posts/category', 'category_url' => 'biser']) ?>">
						<span class="text-label">Бисер</span>
					</a>
				</li>

				<li class="menu-not-biser">
					<a href="<?= Url::to(['/blogFront/posts/category', 'category_url' => 'not_biser']) ?>">
						<span class="text-label">Не бисер</span>
					</a>
				</li>

				<li class="menu-contacts">
					<a href="<?= Url::to(['/pageFront/page/view', 'title_url' => $pageModule->getPageUrlById(Page::PAGE_ID_CONTACTS)]) ?>">
						<span class="text-label">Контакты</span>
					</a>
				</li>
			</ul>
		</div>
	<?php Spaceless::end() ?>

</header>