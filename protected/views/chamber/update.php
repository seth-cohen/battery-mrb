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
	array('label'=>'View This Chamber', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Add New Chamber', 'url'=>array('create')),
	array('label'=>'View All Chambers', 'url'=>array('index')),
	array('label'=>'Chamber Admin', 'url'=>array('admin'), 'visibility'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Edit Chamber <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>