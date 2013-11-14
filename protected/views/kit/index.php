<?php
/* @var $this KitController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Kits',
);

$this->menu=array(
	array('label'=>'Create Kit', 'url'=>array('create')),
	array('label'=>'Manage Kit', 'url'=>array('admin')),
);
?>

<h1>Kits</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
