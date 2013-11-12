<?php
/* @var $this ManufacturingController */
/* @var $model Cathode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Cathodes'=>array('index'),
    'Create',
);

$this->menu=array(
	array('label'=>'Create Anode Lot', 'url'=>array('createanodelot')),
	array('label'=>'View All Anode Lots', 'url'=>array('viewanodelots')),
    array('label'=>'Create Cathode Lot', 'url'=>array('createcathodelot')),
    array('label'=>'View All Cathode Lots', 'url'=>array('viewcathodelots')),
);
?>

<h1>Create Cathode</h1>

<?php $this->renderPartial('_createcathodeform', array('model'=>$model)); ?>