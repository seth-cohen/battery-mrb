<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Battery', 'url'=>array('index')),
	array('label'=>'Create Battery', 'url'=>array('create')),
	array('label'=>'View Battery', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Battery', 'url'=>array('admin')),
);
?>

<h1>Update Battery <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>