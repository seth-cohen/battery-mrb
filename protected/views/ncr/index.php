<?php
/* @var $this NcrController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ncrs',
);

$this->menu=array(
	array('label'=>'Put Cells on NCR', 'url'=>array('putcellsonncr')),
	array('label'=>'Dispo Cells on NCR', 'url'=>array('dispocellsonncr')),
	array('label'=>'NCR Admin', 'url'=>array('admin')),
);
?>

<h1>NCRs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
