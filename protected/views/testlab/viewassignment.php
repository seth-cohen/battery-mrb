<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $kit Kit */
/* @var $celltype Celltype */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'View Assignment',
);

$this->menu=array(
	array('label'=>'Put cells on Formation', 'url'=>array('cellformation')),
	array('label'=>'View Cells on Formation', 'url'=>array('formationindex')),
	array('label'=>'Put cells on CAT', 'url'=>array('cellcat')),
	array('label'=>'View Cells on CAT', 'url'=>array('catindex')),
	array('label'=>'Condition for Assembly', 'url'=>array('cellconditioning')),
	array('label'=>'View Cells Conditioning', 'url'=>array('conditioningindex')),
	array('label'=>'Miscellaneous Testing', 'url'=>array('misctesting')),
	array('label'=>'View Miscellaneous Tests', 'url'=>array('miscindex')),
	array('label'=>'Edit This Assignment', 'url'=>array('updatetestassignment','id'=>$model->id)),
	array('label'=>'Test Reassignments', 'url'=>array('testreassignment')),
	array('label'=>'Move Cells to Storage', 'url'=>array('storage')),
	array('label'=>'Deliver Cells to Assembly', 'url'=>array('deliverforbattery')),
	array('label'=>'View All Tests (Historic)', 'url'=>array('testindex')),
	array('label'=>'View All Cells', 'url'=>array('/cell/index')),
);
?>

<h1>Details for Test Assignment </h1>

<div class="shadow border">
<?php 
$testType;
if ($model->is_formation) $testType = 'Formation';
elseif($model->is_conditioning) $testType = 'Conditioning';
elseif($model->is_misc) $testType = 'Miscellaneous';
else $testType = 'CAT';

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'serial_search',
			'type'=>'html',
			'value'=>$model->cell->getLink(),
		),
		array(
			'name'=>'chamber_search',
			'value'=>$model->chamber->name,
		),
		array(
			'label'=>'Cycler {Channel}',
			'value'=>$model->channel->cycler->name.'{'.$model->channel->number.'}',
		),
		'test_start',
		array(
			'name' => 'test_start_time',
			'value' => date("H:i", $model->test_start_time),
		),
		'is_active:boolean',
		array(
			'label' => 'Test Type',
			'value'=>$testType,
		),
		'desc',
		array(
			'label' => 'Operator',
			'value'=>User::getFullNameProper($model->operator_id),
		),
	),
	'cssFile'=>Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>


