<?php

use yii\web\View;
use backend\modules\commentBackend\widgets\LastCommentsWidget;
/** @var View $this */
/** @var LastCommentsWidget $lastCommentsWiget */

$this->title = 'Админка';
?>
<div class="site-index">

    <div class="body-content">

        <h1><?= $this->title ?></h1>

		<?= $lastCommentsWiget->run() ?>

    </div>
</div>
