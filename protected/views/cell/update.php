<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $kit Kit */
/* @var $celltype Celltype */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	$model->kit->celltype->name.'-'.$model->kit->serial_num=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View Cell', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Create New Kit', 'url'=>array('kit/create')),
	array('label'=>'Stack Cell (single)', 'url'=>array('stackcell')),
	array('label'=>'Stack Cells (multi)', 'url'=>array('multistackcells')),
	array('label'=>'Fill Cell (single)', 'url'=>array('fillcell')),
	array('label'=>'Fill Cells (multi)', 'url'=>array('multifillcells')),
	array('label'=>'Inspect Cell (single)', 'url'=>array('inspectcell')),
	array('label'=>'Inspect Cells (multi)', 'url'=>array('multiinspectcells')),
	array('label'=>'View All Cells', 'url'=>array('index')),
);
?>

<h1>Update Cell <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'kit'=>$kit, 'celltype'=>$celltype)); ?>