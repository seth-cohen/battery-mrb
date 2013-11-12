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
	array('label'=>'List Cell', 'url'=>array('index')),
	array('label'=>'Create Cell', 'url'=>array('create')),
	array('label'=>'Update Cell', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cell', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cell', 'url'=>array('admin'), 'visible'=>Yii::app()->user->name=='admin'),
);
?>

<h1>Details for Cell #<?php echo $celltype->name.'-'.$kit->serial_num; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'serial_search',
			'value'=>$celltype->name.'-'.$kit->serial_num,
		),
		'ref_num',
		'eap_num',
		array(
			'name'=>'Stacker',
			'value'=>$model->stacker->getFullName(),
		),
		'stack_date',
		'dry_wt',
		'wet_wt',
		array(
			'name'=>'Stacker',
			'value'=>$model->filler->getFullName(),
		),
		'fill_date',
		array(
			'name'=>'Stacker',
			'value'=>$model->inspector->getFullName(),
		),
		'inspection_date',
	),
)); ?>
