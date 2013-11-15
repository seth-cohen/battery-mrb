<?php
/* @var $this ManufacturingController */
/* @var $model Anode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Anodes'=>array('index'),
    'Lot '.$model->lot_num,
);

$this->menu=array(
    array('label'=>'List Cathode', 'url'=>array('viewcathodelots')),
    array('label'=>'Create Cathode', 'url'=>array('createcathodelot')),
    array('label'=>'Edit Cathode Lot', 'url'=>array('updatecathodelot', 'id'=>$model->id)),
    array('label'=>'Delete Cathode', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage Cathode', 'url'=>array('admin')),
);
?>

<h1>Anode Lot <?php echo $model->lot_num; ?> Details</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'lot_num',
        'eap_num',
        array(
        	'label'=>'Coater',
        	'value'=>$model->coater->getFullName(),
        ),
        'coat_date',
        array(
        	'label'=>'Reference No.',
        	'value'=>$model->refNum->number,
        ),
    ),
)); ?>