<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $kit Kit */
/* @var $celltype Celltype */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Create New Kit', 'url'=>array('kit/create')),
	array('label'=>'Stack Cells (multi)', 'url'=>array('multistackcells')),
	array('label'=>'Inspect Cells (multi)', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells (multi)', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells (multi)', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells (multi)', 'url'=>array('multitipoffcells')),
	array('label'=>'Accept CAT Data', 'url'=>array('multiacceptcatdata'), 'visible'=>Yii::app()->user->checkAccess('quality')),
	array('label'=>'View All Cells', 'url'=>array('index')),
);
?>

<h1>Create Cell</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'kit'=>$kit, 'celltype'=>$celltype)); ?>