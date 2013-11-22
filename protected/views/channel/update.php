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
	array('label'=>'List Channel', 'url'=>array('index')),
	array('label'=>'Create Channel', 'url'=>array('create')),
	array('label'=>'View Channel', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Channel', 'url'=>array('admin')),
);
?>

<h1>Update Channel <?php echo $model->number.' ['.$model->cycler->name.']'; ?> </h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>