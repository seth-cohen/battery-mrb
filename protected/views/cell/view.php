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
	array('label'=>'Update Cell', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Create New Kit', 'url'=>array('kit/create')),
	array('label'=>'Stack Cells (multi)', 'url'=>array('multistackcells')),
	array('label'=>'Inspect Cells (multi)', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells (multi)', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells (multi)', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells (multi)', 'url'=>array('multitipoffcells')),
	array('label'=>'View All Cells', 'url'=>array('index')),
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
	),
	'cssFile'=>Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>


