<?php
/* @var $this ChamberController */
/* @var $model Chamber */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Chambers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'View All Chambers', 'url'=>array('index')),
	array('label'=>'Chamber Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Create Chamber</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>