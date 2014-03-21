<?php 
/* @var $this ChamberController */
/* @var $testAssignment TestAssignment */
/* @var $testAssignmentDataProvider CActiveDataProvider */
?>

<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>

<?php 
Yii::app()->clientScript->scriptMap=array(
                    'jquery.yiigridview.js'=>false
                ); 
?>

<h2 style="width:100%; text-align:center">Test Assignment Details for Chamber <?php echo $testAssignment->chamber->name; ?></h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'assignment-grid',
	'dataProvider'=>$testAssignmentDataProvider,
	'filter'=>$testAssignment,
	'columns'=>array(
		array(
			'name'=>'serial_search',
			'type'=>'raw',
			'value'=>function($data, $row){
				return 
				CHtml::link($data->cell->kit->getFormattedSerial(), 
					array("cell/view", "id"=>$data->cell->id)
				);
			}
		),
		'test_start',
		array(
			'name'=>'cycler_search',
			'value'=>'$data->channel->cycler->name." {". $data->channel->number ."}"',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/cell/view",array("id"=>$data->cell->id))',
		),
	),
	'emptyText'=>'Oops, no cells on test in the chamber',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>