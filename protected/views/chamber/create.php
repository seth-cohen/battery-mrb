<?php
/* @var $this ChamberController */
/* @var $model Chamber */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Chambers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Chamber', 'url'=>array('index')),
	array('label'=>'Manage Chamber', 'url'=>array('admin')),
);
?>

<h1>Create Chamber</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>