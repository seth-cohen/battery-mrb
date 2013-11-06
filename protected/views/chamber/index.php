<?php
/* @var $this ChamberController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Chambers',
);

$this->menu=array(
	array('label'=>'Create Chamber', 'url'=>array('create')),
	array('label'=>'Manage Chamber', 'url'=>array('admin')),
);
?>

<h1>Chambers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
