<?php
use backend\modules\commentBackend\widgets\LastCommentsWidget;
use common\base\View;
use common\components\Formatter;

/** @var View $this */
/** @var LastCommentsWidget $widget */

$formatter = new Formatter();
?>

<div class="last-comments-widget">

	<h2>Последние комментарии</h2>

	<?php if (count($widget->lastComments) === 0): ?>

		<div class="alert alert-info">
			Комментарии отсутствуют
		</div>

	<?php else: ?>

		<?php foreach ($widget->lastComments as $comment): ?>
			<div class="commment-item">

				<div class="comment-field timestamp">
					<span class="lbl">Добавлен</span>
					<span class="value"><?= $formatter->asLocalDateTime($comment->insert_stamp, 'd.m.Y H:i') ?></span>
				</div>

				<div class="comment-field author">
					<span class="lbl">Автор</span>
					<span class="value"><?= $comment->nickname ?></span>
				</div>

				<div class="content">
					<?= htmlentities($comment->content) ?>
				</div>

				<div class="actions">

					<?= \yii\helpers\Html::a(
						'Перейти',
						$comment->getRelatedUrl() . '#comment-id-' . $comment->id,
						['target' => '_blank']
					) ?>

				</div>
			</div>
		<?php endforeach ?>

	<?php endif ?>

</div>
