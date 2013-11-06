<?php
/* @var $this CellController */
/* @var $model Cell */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	$model->celltype->name.'-'.$model->serial_num,
);

$this->menu=array(
	array('label'=>'List Cell', 'url'=>array('index')),
	array('label'=>'Create Cell', 'url'=>array('create')),
	array('label'=>'Update Cell', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cell', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cell', 'url'=>array('admin')),
);
?>

<h1>View Cell #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'serial_num',
		'kit_id',
		'ref_num',
		'eap_num',
		'celltype_id',
		'stacker_id',
		'stack_date',
		'dry_wt',
		'wet_wt',
		'filler_id',
		'fill_date',
		'inspector_id',
		'inspection_date',
	),
)); ?>
