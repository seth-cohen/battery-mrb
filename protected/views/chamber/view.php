<?php
/* @var $this ChamberController */
/* @var $model Chamber */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Chambers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Chamber', 'url'=>array('index')),
	array('label'=>'Create Chamber', 'url'=>array('create')),
	array('label'=>'Update Chamber', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Chamber', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Chamber', 'url'=>array('admin')),
);
?>

<h1>View Chamber #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'brand',
		'model',
		'serial_num',
		'in_commission',
		'govt_tag_num',
		'cycler_id',
		array(
			'label'=>'Min Temp',
			'type'=>'raw',
			'value'=>$model->min_temp.' &degC',
		),
		array(
			'label'=>'Max Temp',
			'type'=>'raw',
			'value'=>$model->max_temp.' &degC',
		),
	),
)); ?>
