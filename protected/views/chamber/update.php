<?php
/* @var $this ChamberController */
/* @var $model Chamber */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Chambers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Add New Chamber', 'url'=>array('create')),
	array('label'=>'View Chamber', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'View All Chambers', 'url'=>array('index')),
	array('label'=>'Manage Chambers', 'url'=>array('admin')),
);
?>

<h1>Update Chamber <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>