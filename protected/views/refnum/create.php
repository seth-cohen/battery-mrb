<?php
/* @var $this RefnumController */
/* @var $model RefNum */

$this->breadcrumbs=array(
	'Ref Nums'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List RefNum', 'url'=>array('index')),
	array('label'=>'Manage RefNum', 'url'=>array('admin')),
);
?>

<h1>Create RefNum</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>