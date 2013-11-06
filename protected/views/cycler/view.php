<?php
/* @var $this CyclerController */
/* @var $model Cycler */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Cycler', 'url'=>array('index')),
	array('label'=>'Create Cycler', 'url'=>array('create')),
	array('label'=>'Update Cycler', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Cycler', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Cycler', 'url'=>array('admin')),
);
?>

<h1>View Cycler #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sy_number',
		'name',
		'num_channels',
		'cal_date',
		'cal_due_date',
		array(
			'label'=>'Calibrator',
			'value'=>$model->calibrator->username,
		),
		'maccor_job_num',
		'govt_tag_num',
	),
)); ?>

<h1>Channel Details</h1>
<div> 
	<table>
		<tr><th>number</th><th>availability</th></tr>
		<?php foreach($model->channels as $channel): ?>
		<tr><td><?php echo $channel->number; ?></td><td><?php echo ($channel->in_commission && !$channel->in_use)?'YES':'NO'; ?>
		<?php endforeach; ?>
	</table>
</div>
