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
	array('label'=>'List Cycler', 'url'=>array('index')),
	array('label'=>'Create Cycler', 'url'=>array('create')),
	array('label'=>'View Cycler', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cycler', 'url'=>array('admin')),
);
?>

<h1>Update Cycler <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>