<?php
/* @var $this CellController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells',
);

$this->menu=array(
	array('label'=>'Create Cell', 'url'=>array('create')),
	array('label'=>'Manage Cell', 'url'=>array('admin')),
);
?>

<h1>Cells</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
