<?php
/* @var $this NcrController */
/* @var $model Ncr */

$this->breadcrumbs=array(
	'Ncrs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Ncr', 'url'=>array('index')),
	array('label'=>'Create Ncr', 'url'=>array('create')),
	array('label'=>'Update Ncr', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Ncr', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ncr', 'url'=>array('admin')),
);
?>

<h1>View Ncr #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'number',
		'date',
	),
)); ?>
