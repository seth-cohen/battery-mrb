<?php
/* @var $this CyclerController */
/* @var $model Cycler */
/* @var $channelsDataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View This Cycler', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Add a New Cycler', 'url'=>array('create')),
	array('label'=>'View All Cyclers', 'url'=>array('index')),
	array('label'=>'Cycler Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Edit Cycler <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'channelsDataProvider'=>$channelsDataProvider)); ?>