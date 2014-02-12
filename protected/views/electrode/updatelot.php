<?php
/* @var $this ManufacturingController */
/* @var $model Anode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Electrodes'=>array('index'),
    'Lot '.$model->lot_num,
	'Edit',
);

$this->menu=array(
	array('label'=>'View This Lot', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
    array('label'=>'View All Electrodes', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Update Electrode Lot <?php echo $model->lot_num; ?></h1>

<?php $this->renderPartial('_updateform', array('model'=>$model)); ?>
