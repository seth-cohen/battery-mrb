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
    array('label'=>'Electrodes List', 'url'=>array('index')),
    array('label'=>'Create Electrode', 'url'=>array('create')),
    array('label'=>'Viw All Electrodes', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin')),
);
?>

<h1>Update Electrode Lot <?php echo $model->lot_num; ?></h1>

<?php $this->renderPartial('_createelectrodeform', array('model'=>$model)); ?>
