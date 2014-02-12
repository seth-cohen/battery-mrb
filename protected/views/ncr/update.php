<?php
/* @var $this NcrController */
/* @var $model Ncr */

$this->breadcrumbs=array(
	'Ncrs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View This NCR', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Put Cells on NCR', 'url'=>array('putcellsonncr')),
	array('label'=>'Dispo Cells on NCR', 'url'=>array('dispositioncells')),
	array('label'=>'View All NCRs', 'url'=>array('index')),
	array('label'=>'NCR Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Edit NCR-<?php echo $model->number; ?></h1>

<?php $this->renderPartial('_form', array(
	'model'=>$model, 
	'ncrCellDataProvider'=>$ncrCellDataProvider,
	'ncrCell' =>$ncrCell
)); ?>