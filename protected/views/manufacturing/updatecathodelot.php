<?php
/* @var $this ManufacturingController */
/* @var $model Anode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Cathodes'=>array('index'),
    'Lot '.$model->lot_num,
	'Edit',
);

$this->menu=array(
    array('label'=>'List Cathode', 'url'=>array('viewcathodelots')),
    array('label'=>'Create Cathode', 'url'=>array('createcathodelot')),
    array('label'=>'Edit Cathode Lot', 'url'=>array('updatecathodelot', 'id'=>$model->id)),
    array('label'=>'Delete Cathode', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Cathode', 'url'=>array('admin')),
);
?>

<h1>Update Cell <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_createcathodeform', array('model'=>$model)); ?>
