<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FormBank */
/* @var $form ActiveForm */
?>
<div class="FormBank row">
	<div class="col-md-5 col-md-offset-3">
		<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'username') ?>
		<?= $form->field($model, 'sum') ?>

		<div class="form-group">
			<?= Html::submitButton(Yii::t('app', 'Transfer'), ['class' => 'btn btn-primary']) ?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>

</div><!-- FormBank -->
