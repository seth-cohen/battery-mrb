<?php
/* @var $this ManufacturingController */
/* @var $model Anode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Anodes'=>array('index'),
    'Lot '.$model->lot_num,
	'Edit',
);

$this->menu=array(
    array('label'=>'List Anode', 'url'=>array('viewanodelots')),
    array('label'=>'Create Anode', 'url'=>array('createanodelot')),
    array('label'=>'Edit Anode Lot', 'url'=>array('updateanodelot', 'id'=>$model->id)),
    array('label'=>'Delete Anode', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Anode', 'url'=>array('admin')),
);
?>

<h1>Update Cell <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_createanodeform', array('model'=>$model)); ?>
