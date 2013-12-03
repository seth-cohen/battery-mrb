<?php
/* @var $this KitController */
/* @var $model Kit */

$this->breadcrumbs=array(
	'Kits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Create Kits (Multi)', 'url'=>array('multicreate')),
	array('label'=>'View All Kits', 'url'=>array('index')),
	array('label'=>'Manage Kit', 'url'=>array('admin')),
);
?>

<h1>Create Kit</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>