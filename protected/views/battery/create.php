<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'Add Battery Type', 'url'=>array('addbatterytype')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
);
?>

<h1>Create Battery</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>