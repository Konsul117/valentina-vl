<?php
use yii\helpers\Html;

?>

<header>

	<div class="last-photos">
		<div class="photos-container">
			<div class="photo-item">

			</div>

			<div class="photo-item">

			</div>

			<div class="photo-item">

			</div>

			<div class="photo-item">

			</div>

			<div class="photo-item">

			</div>

			<div class="photo-item">

			</div>
		</div>
	</div>

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
			<div class="social-logo social-logo-instagram"><?= Html::a('', 'https://www.instagram.com/vasa_vasa400/ ', ['target' => '_blank']) ?></div><!--
		 --><div class="social-logo social-logo-ok"><?= Html::a('', 'http://ok.ru/valentina.panchenko1 ', ['target' => '_blank']) ?></div><!--
		 --><div class="social-logo social-logo-vk"><?= Html::a('', 'https://vk.com/id215242627 ', ['target' => '_blank']) ?></div>
		</div>

		<div class="menu">
			<ul class="menu-items">
				<li class="item-biser">
					<?= Html::a('', ['/']) ?>
				</li>

				<li class="item-not-biser">
					<?= Html::a('', ['/']) ?>
				</li>

				<li class="item-contacts">
					<?= Html::a('', ['/']) ?>
				</li>
			</ul>
		</div>

		<div class="search-panel">
			<input type="text" maxlength="50" placeholder="Поиск">
			<div class="icons icons-search">
				<?= Html::a('', ['/']) ?>
			</div>
		</div>
	</div>

</header>