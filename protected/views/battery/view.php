<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Battery', 'url'=>array('index')),
	array('label'=>'Create Battery', 'url'=>array('create')),
	array('label'=>'Update Battery', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Battery', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Battery', 'url'=>array('admin')),
);
?>

<h1>View Battery #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'batterytype_id',
		'ref_num_id',
		'eap_num',
		'serial_num',
		'assembler_id',
		'assembly_date',
		'ship_date',
		'location',
	),
)); ?>
