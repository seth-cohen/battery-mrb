<?php 
/* @var $this ElectrodeController */
/* @var $model Electrode */
/* @var $kitDataProvider CArrayDataProvider */
?>

<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>

<?php 
Yii::app()->clientScript->scriptMap=array(
                    'jquery.yiigridview.js'=>false
                ); 
?>

<h2 style="width:100%; text-align:center">Cells using <?php echo $model->is_anode?'Anode':'Cathode'; ?> Lot <?= $model->lot_num; ?> </h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kit-grid',
	'dataProvider'=>$kitDataProvider,
	'columns'=>array(
		array(
			'name'=>'No.',
			'value'=>'$data["num"]',
		),
		array(
			'name'=>'Cell Serial',
			'type'=>'html',
			'value'=>'CHtml::link(CHtml::encode($data["kit"]), array("cell/view", "id"=>$data["id"]))',
		),
		array(
			'header'=>'Anode Lots',
			'type'=>'raw',
			'value'=>'$data["anodes"]',
			'htmlOptions'=>array('width'=>'150px')
		),
		array(
			'header'=>'Cathode Lots',
			'type'=>'raw',
			'value'=>'$data["cathodes"]',
			'htmlOptions'=>array('width'=>'150px')
		),
		array(
			'name'=>'Stack Date',
			'value'=>'$data["stack_date"]',
		),
		array(
			'name'=>'Cell Location',
			'value'=>'$data["location"]',
			'htmlOptions'=>array('width'=>'150px')
		),
	),
	'emptyText'=>'Oops, no cells built yet',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>