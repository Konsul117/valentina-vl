<?php
use common\base\View;
use common\components\Formatter;
use frontend\modules\commentFront\models\CommentFront;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var $this View */
/** @var CommentFront[] $comments */
/** @var CommentFront $newComment */
/** @var bool $comment_added */

$formatter = new Formatter();
?>

<div class="comment-block">

	<?php if ($comment_added === true): ?>
		<div class="alert alert-success">
			Ваш комментарий успешно добавлен
		</div>
	<?php endif ?>

	<?php if (!empty($comments)): ?>
		<div class="comments-list">
			<?php foreach ($comments as $comment): ?>
				<div class="comment-item">
					<?= Html::a(null, null, ['id' => 'comment-id-' . $comment->id]) ?>

					<div class="comment-fied name">
						<?= $comment->nickname ?>
					</div>

					<div class="comment-fied date">
						<?= $formatter->asLocalDateTime($comment->insert_stamp, 'd.m.Y H:i') ?>
					</div>

					<div class="comment-fied comment-content">
						<?= htmlentities($comment->content) ?>
					</div>
				</div>
			<?php endforeach ?>

		</div>
	<?php endif ?>

	<div class="add-comment">

		<h2>Добавить комментарий</h2>

		<?php $form = ActiveForm::begin([
			//			'action' => ['/commentFront/add-comment/add'],
				'options' => ['id' => 'contactForm', 'autocomplete' => 'off'],
		]); ?>

		<?= Html::hiddenInput('redirect_route', Url::current()) ?>

		<?= $form->field($newComment, CommentFront::ATTR_NICKNAME)->textInput(['maxlength' => 100]) ?>

		<?= $form->field($newComment, CommentFront::ATTR_CONTENT)->textarea(['maxlength' => 1000]) ?>

		<div class="form-group">
			<?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
		</div>

		<?php ActiveForm::end(); ?>


	</div>

</div>
