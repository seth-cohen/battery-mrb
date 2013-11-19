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
    array('label'=>'Edit Electrode Lot', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete Electrode', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Electrode Admin', 'url'=>array('admin')),
);
?>

<h1>Update Electrode Lot <?php echo $model->lot_num; ?></h1>

<?php $this->renderPartial('_createelectrodeform', array('model'=>$model)); ?>
