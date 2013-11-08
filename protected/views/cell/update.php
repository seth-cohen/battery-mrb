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
	array('label'=>'List Cell', 'url'=>array('index')),
	array('label'=>'Create Cell', 'url'=>array('create')),
	array('label'=>'View Cell', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Cell', 'url'=>array('admin')),
);
?>

<h1>Update Cell <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'kit'=>$kit, 'celltype'=>$celltype)); ?>