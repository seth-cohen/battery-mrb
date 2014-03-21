<?php 
/* @var $this cyclerController */
/* @var $testAssignment TestAssignment */
/* @var $testAssignmentDataProvider CActiveDataProvider */
?>

<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>

<?php 
Yii::app()->clientScript->scriptMap=array(
                    'jquery.yiigridview.js'=>false
                ); 
?>

<?php if($testAssignment == null):?>
<?php else: ?>

<h2 style="width:100%; text-align:center">Test Assignment Details for Cycler <?php echo $testAssignment->cycler_search ?></h2>
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
		array(
			'header'=>'Channel No.',
			'value'=>'$data->channel->number'
		),
		'test_start',
		array(
			'name'=>'chamber_search',
			'value'=>'$data->chamber->name',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/cell/view",array("id"=>$data->cell->id))',
		),
	),
	'emptyText'=>'Oops, no cells on test on this Cycler',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>

<?php endif; ?>
