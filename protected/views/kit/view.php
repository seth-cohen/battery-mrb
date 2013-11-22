<?php
/* @var $this KitController */
/* @var $model Kit */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Kits'=>array('index'),
	$model->getFormattedSerial(),
);

$this->menu=array(
	array('label'=>'Create New Kit', 'url'=>array('create')),
	array('label'=>'Edit Kit', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'View All Kits', 'url'=>array('index')),
	array('label'=>'Manage Kit', 'url'=>array('admin')),
);
?>

<h1>View Kit details for Cell <?php echo $model->getFormattedSerial(); ?></h1>

<div class="shadow border">
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
			'value'=>$model->getAnodeList(),
		),
		array(
			'label'=>'Cathode Lots',
			'value'=>$model->getCathodeList(),
		),
		array(
			'label'=>'Kitter',
			'value'=>$model->kitter->getFullName(),
		),
		'kitting_date',
		array(
			'label'=>'Cell Link',
			'type'=>'html',
			'value'=>(empty($model->cells))?'Not stacked yet':CHtml::link('View Cell Details', $this->createUrl('cell/view', array('id'=>$model->cells[0]->id))),
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>