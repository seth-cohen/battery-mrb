<?php
/* @var $this BatteryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Batteries',
);

$this->menu=array(
	array('label'=>'Create Battery', 'url'=>array('create')),
	array('label'=>'Manage Battery', 'url'=>array('admin')),
);
?>

<h1>Batteries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
