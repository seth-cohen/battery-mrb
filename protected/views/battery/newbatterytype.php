<?php
/* @var $this BatteryController */
/* @var $batterytypeModel Battery */
/* @var $typeDataProvider  CActiveDataProvider */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	'New Battery Type',
);

$this->menu=array(
	array('label'=>'Assemble Battery', 'url'=>array('assemble')),
	array('label'=>'Accept Test Data', 'url'=>array('accepttestdata'), 'visible'=>Yii::app()->user->checkAccess('quality')),
	array('label'=>'Ship Batteries', 'url'=>array('ship')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

?>

<h1>Add a New Battery Type</h1>

<div id="batterytype-wrapper" style="padding-bottom:10px;">
	<?php $this->renderPartial('_addbatterytype', array('batterytypeModel'=>$batterytypeModel)); ?>
</div>

<div class="shadow border" id="batterytype-wrapper" style="margin:auto;"> 
<h2 style="text-align:center">Existing Battery Types</h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>"batterytypes-grid",
	'dataProvider'=>$typeDataProvider,
	'columns'=>array(
		'part_num',
		'name',
		'num_cells',
		array(
			'name'=>'cell_type',
			'value'=>'$data->celltype->name',
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>
</div>
