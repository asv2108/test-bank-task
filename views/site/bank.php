<?php
use app\assets\AppAsset;
AppAsset::register($this);
Yii::$app->view->registerJsFile('/js/bank.js',['depends'=>\yii\bootstrap\BootstrapAsset::className()]);
?>
<div class="col-xs-12">
	<?=\yii\grid\GridView::widget([
		'dataProvider'=>$data,
		'columns'=>[
			[
				'label'=>Yii::t('app','Username'),
				'attribute'=>'username'
			],
			[
				'label'=>Yii::t('app','Баланс'),
				'attribute'=>'balance'
			],
		]
	])?>
</div>