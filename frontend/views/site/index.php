<?php

use common\base\View;
use yii\base\Widget;

/** @var $this View */
/** @var Widget $postsWidget */

$this->title = 'Главная';
?>

<?php if ($postsWidget !== null): ?>
	<?= $postsWidget->run() ?>
<?php endif ?>
