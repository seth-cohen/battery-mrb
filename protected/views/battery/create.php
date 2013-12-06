<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Battery', 'url'=>array('index')),
	array('label'=>'Manage Battery', 'url'=>array('admin')),
);
?>

<h1>Create Battery</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>