<?php
/* @var $this ManufacturingController */
/* @var $model Anode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Anodes'=>array('viewanodelots'),
    'Create',
);

$this->menu=array(
	array('label'=>'Create Anode Lot', 'url'=>array('createanodelot')),
	array('label'=>'View All Anode Lots', 'url'=>array('viewanodelots')),
    array('label'=>'Create Cathode Lot', 'url'=>array('createcathodelot')),
    array('label'=>'View All Cathode Lots', 'url'=>array('viewcathodelots')),
);
?>

<h1>Create Anode</h1>

<?php $this->renderPartial('_createanodeform', array('model'=>$model)); ?>