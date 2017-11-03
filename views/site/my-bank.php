<div class="row">
	<div class="col-md-6 col-md-offset-2">
		<?=\yii\grid\GridView::widget([
			'dataProvider'=>$data,
			'columns'=>[
				[
					'label'=>Yii::t('app','Id'),
					'attribute'=>'id'
				],
				[
					'label'=>Yii::t('app','Balance'),
					'attribute'=>'sum'
				],
				[
					'label'=>Yii::t('app','User'),
					'attribute'=>'info'
				],
				[
					'label'=>Yii::t('app','Status'),
					'attribute'=>'status'
				],
			]
		])?>
	</div>
</div>
