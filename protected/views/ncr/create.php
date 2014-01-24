<?php
/* @var $this NcrController */
/* @var $model Ncr */

$this->breadcrumbs=array(
	'Ncrs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ncr', 'url'=>array('index')),
	array('label'=>'Manage Ncr', 'url'=>array('admin')),
);
?>

<h1>Create Ncr</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>