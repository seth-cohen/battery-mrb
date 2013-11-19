<?php
/* @var $this ManufacturingController */
/* @var $model Anode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Electrodes'=>array('index'),
    'Create',
);

$this->menu=array(
    array('label'=>'Electrodes List', 'url'=>array('index')),
    array('label'=>'Create Electrode', 'url'=>array('create')),
    array('label'=>'Electrode Admin', 'url'=>array('admin')),
);
?>

<h1>Create Electrode Lot</h1>

<?php $this->renderPartial('_createelectrodeform', array('model'=>$model)); ?>