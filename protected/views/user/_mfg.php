<?php
/* @var $this UserController */
/* @var $cellDataProvider CArrayDataProvider */
?>

<div class="shadow border">
<h2>Manufacturing Employee Details</h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cell-grid',
	'dataProvider'=>$cellDataProvider,
	'columns'=>array(
		array(
			'name'=>'No.',
			'value'=>'$data["num"]',
		),
		array(
			'name'=>'Cells Stacked',
			'value'=>'$data["serial"]',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/cell/view",array("id"=>$data["id"]))',
		),
	),
	'emptyText'=>'Oops, no cells stacked for User',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>
</div>
