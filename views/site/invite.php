<?php
use yii\helpers\Html;
?>
<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<h2 class="success-tr">Registration done! </h2>
		<?= Html::a('Go to log in','/site/login',[
			'class'=>"",
		]) ?>
	</div>
</div>