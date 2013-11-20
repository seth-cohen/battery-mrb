<?php
/* @var $this KitController */
/* @var $model Kit */

$this->breadcrumbs=array(
	'Kits'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create New Kit', 'url'=>array('create')),
	array('label'=>'View Kit', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'View All Kits', 'url'=>array('index')),
	array('label'=>'Manage Kit', 'url'=>array('admin')),
);
?>

<h1>Edit Kit <?php echo $model->getFormattedSerial(); ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>