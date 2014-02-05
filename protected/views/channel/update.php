<?php
/* @var $this ChannelController */
/* @var $model Channel */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Channels'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'View This Channel', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'View All Channels', 'url'=>array('index')),
	array('label'=>'Add New Cycler', 'url'=>array('/cycler/create')),
	array('label'=>'Channel Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Edit Channel <?php echo $model->number.' ['.$model->cycler->name.']'; ?> </h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>