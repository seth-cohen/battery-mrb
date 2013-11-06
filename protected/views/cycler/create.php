<?php
/* @var $this CyclerController */
/* @var $model Cycler */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Cycler', 'url'=>array('index')),
	array('label'=>'Manage Cycler', 'url'=>array('admin')),
);
?>

<h1>Create Cycler</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>