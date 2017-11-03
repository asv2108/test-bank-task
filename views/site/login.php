<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

//Yii::$app->view->registerCssFile('/css/login.css');

$this->title='Login';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login row">
	<div class="col-lg-9 col-lg-offset-3">
		<p>Please fill out the following fields to login:</p>
		<?php $form=ActiveForm::begin([
			'id'=>'login-form',
			'layout'=>'horizontal',
			'fieldConfig'=>[
				'template'=>"{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-7\">&nbsp;</div><div class=\"col-lg-8\">{error}</div>",
				'labelOptions'=>['class'=>'col-lg-1 control-label'],
			],
		]); ?>

		<?=$form->field($model,'username')->textInput(['autofocus'=>true])?>


		<div class="form-group">
			<div class="col-lg-offset-1 col-lg-11">
				<?=Html::submitButton('Login',[
					'class'=>'btn btn-primary',
					'name'=>'login-button'
				])?>
			</div>
		</div>

		<?php ActiveForm::end(); ?>
	</div>

</div>
