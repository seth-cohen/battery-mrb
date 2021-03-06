<?php
/* @var $this ChamberController */
/* @var $model Chamber */
/* @var $testAssignment TestAssignment */
/* @var $testAssignmentDataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Chambers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Edit This Chamber', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Add New Chamber', 'url'=>array('create')),
	array('label'=>'View All Chambers', 'url'=>array('index')),
	array('label'=>'Manage Chambers', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Chamber <?php echo $model->name; ?> Details</h1>

<div class="shadow border">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'brand',
		'model',
		'serial_num',
		'in_commission:boolean',
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
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>


<div class="shadow border">
<h2 style="width:100%; text-align:center">Assignment Details for Chamber <?php echo $model->name; ?></h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'assignment-grid',
	'dataProvider'=>$testAssignmentDataProvider,
	'filter'=>$testAssignment,
	'columns'=>array(
		array(
			'name'=>'serial_search',
			'value'=>'$data->cell->kit->getFormattedSerial()',
		),
		array(
			'header'=>'channel',
			'value'=>'$data->channel->cycler->name."-[".$data->channel->number."]"'
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/cell/view",array("id"=>$data->cell->id))',
		),
	),
	'emptyText'=>'Oops, no cells on test in the chamber',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>
</div>

