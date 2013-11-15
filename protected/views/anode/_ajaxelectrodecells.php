<?php 
/* @var $this AnodeController */
/* @var $model Cell */
?>

<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>

<h2>Cell <?= $model->kit->celltype->name.'-'.$model->kit->serial_num; ?> manufacturing details</h2>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'serial_search',
			'value'=>$model->kit->celltype->name.'-'.$model->kit->serial_num,
		),
		array(
			'name'=>'refnum_search',
			'value'=>$model->refNum->number,
		),
		'eap_num',
		array(
			'name'=>'stacker_search',
			'value'=>$model->stacker->getFullName(),
		),
		'stack_date',
		'dry_wt',
		'wet_wt',
		array(
			'name'=>'filler_search',
			'value'=>$model->filler->getFullName(),
		),
		'fill_date',
		array(
			'name'=>'inspector_search',
			'value'=>$model->inspector->getFullName(),
		),
		'inspection_date',
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); 
?>