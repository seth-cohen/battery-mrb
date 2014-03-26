<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $kit Kit */
/* @var $celltype Celltype */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	$celltype->name.'-'.$kit->serial_num,
);

$this->menu=array(
	array('label'=>'Edit This Cell', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Create Kits', 'url'=>array('kit/multicreate')),
	array('label'=>'Stack Cells', 'url'=>array('multistackcells')),
	array('label'=>'Cover Attachment', 'url'=>array('multiattachcells')),
	array('label'=>'Inspect Cells', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells', 'url'=>array('multitipoffcells')),
	array('label'=>'Accept CAT Data', 'url'=>array('multiacceptcatdata'), 'visible'=>Yii::app()->user->checkAccess('quality')),
	array('label'=>'View All Cells', 'url'=>array('index')),
	array('label'=>'Cell Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Details for Cell #<?php echo $celltype->name.'-'.$kit->serial_num; ?></h1>

<div class="shadow border">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'serial_search',
			'value'=>$celltype->name.'-'.$kit->serial_num,
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
	'cssFile'=>Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>


