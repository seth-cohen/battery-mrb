<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	$model->batterytype->name. ' SN: ' .$model->serial_num,
);

$this->menu=array(
	array('label'=>'Edit This Battery', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->batterytype->name; ?> SN: <?php echo $model->serial_num; ?> Details</h1>

<div class="shadow border" >
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'batterytype.name:text:Battery Type',
		'serial_num',
		'refNum.number:text:Reference No.',
		'eap_num',
		array(
			'label'=>'Assembler',
			'value'=>$model->assembler->getFullName(),
		),
		'assembly_date',
		'ship_date',
		'location',
	),
	'cssFile' => Yii::app()->baseUrl.'/css/styles.css',
)); ?>
</div>

<div id="battery-details" class="shadow border" >
<?php  $this->renderPartial('_batterycells', array(
		'model'=>$model,
		'cellDataProvider'=>$cellDataProvider,
	), 
	false, 
	false
);?>
</div>