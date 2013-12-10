<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Edit This Battery', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
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
