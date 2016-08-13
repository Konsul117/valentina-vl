<?php
use common\base\View;
use common\modules\page\models\Page;
use Faker\Provider\Uuid;
use frontend\modules\blogFront\BlogFront;
use frontend\modules\pageFront\PageFront;
use yii\helpers\Html;
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

	<div class="header-sup">
		<?= Html::a(
				Html::tag('div', '', ['class' => 'inner']),
				['/'],
				[
					'alt'   => 'Авторский блог Валентины Панченко',
					'title' => 'Авторский блог Валентины Панченко',
				]
		) ?>
	</div>

	<?= $blogFrontModule->getImagePostsWidget(10)->run() ?>

	<?php Spaceless::begin() ?>
	<div class="img-name-wrapper">
		<div class="img-name-container">
			<div class="img-name">
				<?= Html::a('', ['/'], [
						'alt' => 'Авторский блог Валентины Панченко',
						'title' => 'Авторский блог Валентины Панченко',
				]) ?>
			</div>
			<div class="beads-photo"></div>
		</div>

		<? $seachPanelGuid = Uuid::uuid(); ?>
		<div class="search-heading" id="<?= $seachPanelGuid ?>">
			<div class="search-panel hidden" data-role="search-panel">
				<?= $blogFrontModule->getSearchWidget('btn btn-panel-search')->run() ?>
			</div>
			<button class="btn-header-search glyphicon glyphicon-search" data-role="search-panel-open"></button>
		</div>

		<?= $this->registerJs('$("#' . $seachPanelGuid . '").searchHeadingPanel();') ?>
	</div>
	<?php Spaceless::end() ?>

	<div class="sup-menu">

	</div>

	<?php Spaceless::begin() ?>
		<div class="menu-wrapper">

			<ul class="menu">
				<?php
				/** @var PageFront $pageModule */
				$pageModule       = Yii::$app->modules['pageFront'];
				?>

				<li class="item-about-me">
					<a href="<?= Url::to(['/pageFront/page/view', 'title_url' => $pageModule->getPageUrlById(Page::PAGE_ID_MAIN)]) ?>">
						<span class="text-label">Обо мне</span>
					</a>
				</li>

				<li class="item-biser">
					<a href="<?= Url::to(['/blogFront/posts/category', 'category_url' => 'biser']) ?>">
						<span class="text-label">Бисер</span>
					</a>
				</li>

				<li class="item-not-biser">
					<a href="<?= Url::to(['/blogFront/posts/category', 'category_url' => 'not_biser']) ?>">
						<span class="text-label">Не бисер</span>
					</a>
				</li>

				<li class="item-contacts">
					<a href="<?= Url::to(['/pageFront/page/view', 'title_url' => $pageModule->getPageUrlById(Page::PAGE_ID_CONTACTS)]) ?>">
						<span class="text-label">Контакты</span>
					</a>
				</li>
			</ul>

			<?= $blogFrontModule->getSearchWidget()->run() ?>
		</div>
	<?php Spaceless::end() ?>

</header>