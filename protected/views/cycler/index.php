<?php
/* @var $this CyclerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers',
);

$this->menu=array(
	array('label'=>'Create Cycler', 'url'=>array('create')),
	array('label'=>'Manage Cycler', 'url'=>array('admin')),
);
?>

<h1>Cyclers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
