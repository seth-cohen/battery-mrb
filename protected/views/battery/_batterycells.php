<?php
/* @var $this BatteryController */
/* @var $model Battery */
/* @var $cellDataProvider  CArrayDataProvider */

?>

<h2>Battery <?php echo $model->batterytype->name; ?> SN: <?php echo $model->serial_num; ?> Cell Details</h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>"batterycells-grid",
	'dataProvider'=>$cellDataProvider,
	'columns'=>array(
		array(
			'name'=>'Battery Position',
			'value'=>'$data["position"]>1000?"Spare - ". ($data["position"]-1000): $data["position"]',
		),
		array(
			'header'=>'Cell Serial',
			'value'=>'$data["serial"]',
		),
		array(
			'header'=>'Current Location',
			'value'=>'$data["location"]',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/cell/view",array("id"=>$data["id"]))',
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>