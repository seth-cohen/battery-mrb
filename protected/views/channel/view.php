<?php
/* @var $this ChannelController */
/* @var $model Channel */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Channels'=>array('index'),
	$model->number.' ['.$model->cycler->name.']',
);

$this->menu=array(
	array('label'=>'List Channel', 'url'=>array('index')),
	array('label'=>'Create Channel', 'url'=>array('create')),
	array('label'=>'Update Channel', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Channel', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Channel', 'url'=>array('admin')),
);
?>

<h1>View Channel : <?php echo $model->number.' ['.$model->cycler->name.']'; ?></h1>

<?php echo CHtml::dropDownList('cycler_id', 'name', Cycler::forList(), 
		array(
			'ajax'=>array(
				'type'=>'POST',
				'url'=>CController::createUrl('channel/dynamicChannels'),
				'update'=>'#channel_id',
				'data'=>array('cycler_id'=>'js:this.value'),
			),
			'style'=>'display:block; float:left; margin-right:5px;'
		))?>
		
<?php echo CHtml::dropDownList('channel_id', '-Select Cycler-', array(),
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



<div class="shadow border">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'number',
		array(
			'label'=>'Cycler',
			'value'=>$model->cycler->name,
		),
		'max_charge_rate',
		'max_discharge_rate',
		'multirange:boolean',
		'in_use:boolean',
		'in_commission:boolean',
		'min_voltage',
		'max_voltage',
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>
