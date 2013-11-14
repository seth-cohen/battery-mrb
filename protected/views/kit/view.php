<?php
/* @var $this KitController */
/* @var $model Kit */

$this->breadcrumbs=array(
	'Kits'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Kit', 'url'=>array('index')),
	array('label'=>'Create Kit', 'url'=>array('create')),
	array('label'=>'Update Kit', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Kit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Kit', 'url'=>array('admin')),
);
?>

<h1>View Kit #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'serial_num',
		'ref_num_id',
		'anode_id',
		'cathode_id',
		'kitter_id',
		'kitting_date',
		'celltype_id',
	),
)); ?>
