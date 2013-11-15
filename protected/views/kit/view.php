<?php
/* @var $this KitController */
/* @var $model Kit */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Kits'=>array('index'),
	$model->getFormattedSerial(),
);

$this->menu=array(
	array('label'=>'List Kit', 'url'=>array('index')),
	array('label'=>'Create Kit', 'url'=>array('create')),
	array('label'=>'Update Kit', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Kit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Kit', 'url'=>array('admin')),
);
?>

<h1>View Kit details for Cell <?php echo $model->getFormattedSerial(); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'label'=>'Cell Type',
			'value'=>$model->celltype->name,
		),
		array(
			'label'=>'Serial No.',
			'value'=>$model->celltype->name.'-'.$model->serial_num,
		),
		array(
			'label'=>'Reference No.',
			'value'=>$model->refNum->number,
		),
		'eap_num',
		array(
			'label'=>'Anode Lots',
			'value'=>$model->getElectrodesList(1),
		),
		array(
			'label'=>'Cathode Lots',
			'value'=>$model->getElectrodesList(0),
		),
		array(
			'label'=>'Kitter',
			'value'=>$model->kitter->getFullName(),
		),
		'kitting_date',
	),
)); ?>
