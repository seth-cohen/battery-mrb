<?php
/* @var $this BatteryController */
/* @var $model Battery */
/* @var $cellDataProvider  CArrayDataProvider */
/* @var $spareOptions  Array */
?>

<h2 style="text-align:center">Battery <?php echo $model->batterytype->name; ?> SN: <?php echo $model->serial_num; ?> Cell Details</h2>

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
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data["serial"]), array("cell/view", "id"=>$data["id"]))',
		),
		array(
			'header'=>'Current Location',
			'value'=>'$data["location"]',
		),
		array(
			'header'=>'Notes',
			'value'=>'$data["notes"]'
		),
		array(
			'header'=>'Spares',
			'type'=>'raw',
			'value'=>function($data, $row) use ($spareOptions) {
				if ($data['position'] < 1000)
				{
					return	 CHtml::dropDownList('Battery[Cells]['.$data['id'].']', '', $spareOptions, array(
							'prompt'=>'-N/A-',
							'class'=>'cell-dropdown cells',
							'onchange'=>'spareUsed(this)',
							'style'=>'width:150px',
					));
				}
			},
			'visible'=>($this->action->id == 'update' && $model->data_accepted !=1),
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