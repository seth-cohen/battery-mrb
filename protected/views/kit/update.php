<?php
/* @var $this KitController */
/* @var $model Kit */

$this->breadcrumbs=array(
	'Kits'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View This Kit', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Create Kits', 'url'=>array('multicreate')),
	array('label'=>'View All Kits', 'url'=>array('index')),
	array('label'=>'Kit Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Edit Kit <?php echo $model->getFormattedSerial(); ?> <?php echo $model->is_stacked?'(stacked)':'';?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>