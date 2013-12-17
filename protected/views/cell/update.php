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
	array('label'=>'View Cell', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Create New Kit', 'url'=>array('kit/create')),
	array('label'=>'Stack Cells (multi)', 'url'=>array('multistackcells')),
	array('label'=>'Inspect Cells (multi)', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells (multi)', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells (multi)', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells (multi)', 'url'=>array('multitipoffcells')),
	array('label'=>'View All Cells', 'url'=>array('index')),
);
?>

<h1>Update Cell <?php echo $model->kit->getFormattedSerial(); ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'kit'=>$kit, 'celltype'=>$celltype)); ?>