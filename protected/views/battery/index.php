<?php
/* @var $this BatteryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'All Batteries',
);

$this->menu=array(
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
);
?>

<h1>Batteries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
