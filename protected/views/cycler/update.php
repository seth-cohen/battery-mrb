<?php
/* @var $this CyclerController */
/* @var $model Cycler */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add New Cycler', 'url'=>array('create')),
	array('label'=>'View All Cyclers', 'url'=>array('index')),
	array('label'=>'Manage Cyclers', 'url'=>array('admin')),
);
?>

<h1>Update Cycler <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>