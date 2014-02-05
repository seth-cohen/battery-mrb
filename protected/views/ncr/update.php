<?php
/* @var $this NcrController */
/* @var $model Ncr */

$this->breadcrumbs=array(
	'Ncrs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ncr', 'url'=>array('index')),
	array('label'=>'Create Ncr', 'url'=>array('create')),
	array('label'=>'View Ncr', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Ncr', 'url'=>array('admin')),
);
?>

<h1>Edit NCR-<?php echo $model->number; ?></h1>

<?php $this->renderPartial('_form', array(
	'model'=>$model, 
	'ncrCellDataProvider'=>$ncrCellDataProvider,
	'ncrCell' =>$ncrCell
)); ?>