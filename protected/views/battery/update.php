<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Edit',
);

$this->menu=array(
	array('label'=>'View This Battery', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
);
?>

<h1>Update Battery <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>