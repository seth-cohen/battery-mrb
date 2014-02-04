<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $kit Kit */
/* @var $celltype Celltype */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	$model->kit->getFormattedSerial()=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View This Cell', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Create Kits', 'url'=>array('kit/multicreate')),
	array('label'=>'Stack Cells', 'url'=>array('multistackcells')),
	array('label'=>'Inspect Cells', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells', 'url'=>array('multitipoffcells')),
	array('label'=>'Accept CAT Data', 'url'=>array('multiacceptcatdata'), 'visible'=>Yii::app()->user->checkAccess('quality')),
	array('label'=>'View All Cells', 'url'=>array('index')),
	array('label'=>'Cell Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Edit Cell <?php echo $model->kit->getFormattedSerial(); ?> Details</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'kit'=>$kit, 'celltype'=>$celltype)); ?>