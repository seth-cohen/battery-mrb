<?php
/* @var $this ChannelController */
/* @var $model Channel */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Channels'=>array('index'),
	$model->number.' ['.$model->cycler->name.']',
);

$this->menu=array(
	array('label'=>'Edit This Channel', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'View All Channels', 'url'=>array('index')),
	array('label'=>'Add New Cycler', 'url'=>array('/cycler/create')),
	array('label'=>'Channel Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>View Channel : <?php echo $model->number.' ['.$model->cycler->name.']'; ?></h1>

<div class="shadow border">
<?php echo CHtml::label('Quick Navigate', 'Cycler', array('style'=>'display:block; clear:both; margin-left:5px;'));?>
<?php echo CHtml::dropDownList('cycler_id', $model->cycler_id, Cycler::forList(), 
		array(
			'ajax'=>array(
				'type'=>'POST',
				'url'=>CController::createUrl('channel/dynamicChannels'),
				'update'=>'#channel_id',
				'data'=>array('cycler_id'=>'js:this.value'),
			),
			'style'=>'display:block; float:left; margin-right:5px;'
		))?>
		
<?php echo CHtml::dropDownList('channel_id', $model->id, 
		CHtml::listData(Channel::model()->findAllByAttributes(array('cycler_id'=>$model->cycler_id)), 'id', 'number'),
		array(
			'onchange'=>
				'url = "'.CController::createUrl('channel/').'";
				url += "/"+this.value;
				window.location=url;',
			'style'=>'display:block; float:left; width:50px; margin-right:15px;',
		))?>
		
<?php 
	/* make sure this isn't the last channel for the cycler */
	if ($model->number >= $model->cycler->channelCount)
	{
		//do nothing
	}
	else 
	{
		echo CHtml::link('Next Channel', 
							array(
								'channel/view',
								'id'=>$model->id+1,
							),
							array(
								'style'=>'float:right; margin-left:25px; margin-right:25px;',
							));
	}?>
<?php 
	/* make sure this isn't the first channel for the cycler */
	if ($model->number == 1)
	{
		//do nothing
	}
	else 
	{
		echo CHtml::link('Previous Channel', 
							array(
								'channel/view',
								'id'=>$model->id-1,
							),
							array(
								'style'=>'float:right;',
							));
	}?>


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'number',
		array(
			'label'=>'Cycler',
			'type'=>'raw',
			'value'=>function($data){
				return 
				CHtml::link($data->cycler->name, array('cycler/view', 'id'=>$data->cycler_id));
			},
		),
		'max_charge_rate',
		'max_discharge_rate',
		'multirange:boolean',
		'in_use:boolean',
		'in_commission:boolean',
		'min_voltage',
		'max_voltage',
		array(
			'label'=>'Active Test Cell',
			'type'=>'raw',
			'value'=>function($data){
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
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>
