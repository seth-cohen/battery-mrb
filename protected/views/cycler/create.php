<?php
/* @var $this CyclerController */
/* @var $model Cycler */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'View All Cyclers', 'url'=>array('index')),
	array('label'=>'Cycler Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Create Cycler</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'channelsDataProvider'=>$channelsDataProvider)); ?>
