<?php
/* @var $this CyclerController */
/* @var $model Cycler */
/* @var $channel Channel */
/* @var $channelDataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Edit This Cycler', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Add New Cycler', 'url'=>array('create')),
	array('label'=>'View All Cyclers', 'url'=>array('index')),
	array('label'=>'Cycler Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>View <?php echo $model->name; ?> Details</h1>

<div class="shadow border">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'sy_number',
		'num_channels',
		'cal_date',
		'cal_due_date',
		array(
			'label'=>'Calibrator',
			'value'=>$model->calibrator->getFullName(),
		),
		'maccor_job_num',
		'govt_tag_num',
	),
	'cssFile'=>Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>

<div class="shadow border">
<h2 style="text-align:center">Channel Details for <?php echo $model->name; ?></h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'channel-grid',
	'dataProvider'=>$channelDataProvider,
	'filter'=>$channel,
	'columns'=>array(
		'number',
		array(
			'name'=>'in_use',
			'value'=>'($data->in_use)?"Yes":"No"',
			'filter'=>array(''=>'All', '0'=>'No', '1'=>'Yes'),
		),
		array(
			'header'=>'Cell Serial',
			'type'=>'raw',
			'value'=>function($data, $row){
				if($data->activeTestAssignment != null)
				{
					return CHtml::link($data->activeTestAssignment->cell->kit->getFormattedSerial(), 
									array("cell/view", "id"=>$data->activeTestAssignment->cell->id)
								);
				}
				else 
				{
					return 'No Active Test';
				}
			},
			'htmlOptions'=>array('style'=>'width:150px;'),
		),
		'max_charge_rate',
		'max_discharge_rate',
		'min_voltage',
		'max_voltage',
		array(
			'name'=>'multirange',
			'type'=>'boolean',
			'filter'=>array(''=>'All', '0'=>'No', '1'=>'Yes'),
		),
		array(
			'name'=>'in_commission',
			'value'=>'($data->in_commission)?"Yes":"No"',
			'filter'=>array(''=>'All', '0'=>'No', '1'=>'Yes'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/channel/view",array("id"=>$data["id"]))',
		),
	),
	'emptyText'=>'Oops, no cells built yet',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>
</div>
