<?php
/* @var $this ManufacturingController */
/* @var $model Anode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Electrodes'=>array('index'),
    'Create New Lot',
);

$this->menu=array(
    array('label'=>'View All Electrodes', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Create Electrode Lot</h1>

<?php $this->renderPartial('_createelectrodeform', array('model'=>$model)); ?>