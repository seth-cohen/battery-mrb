<?php 
/* @var $this CellController */
/* @var $model Cell */
?>

<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>

<h2 style="text-align:center">Cell <?= $model->kit->celltype->name.'-'.$model->kit->serial_num; ?> MFG Details</h2>

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
			'label'=>'Anode Lots',
			'value'=>$model->kit->getAnodeList(),
		),
		array(
			'label'=>'Cathode Lots',
			'value'=>$model->kit->getCathodeList(),
		),
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
		'location',
		array(
			'label'=>'Battery Link',
			'type'=>'html',
			'value'=>($model->battery==null)?'Not in a Battery Yet':CHtml::link('View Battery Details', $this->createUrl('battery/view', array('id'=>$model->battery->id))),
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); 
?>