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
<h2>Kits using <?php echo $model->is_anode?'Anode':'Cathode'; ?> Lot <?= $model->lot_num; ?> </h2>


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
			'value'=>'$data["kit"]',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/kit/view",array("id"=>$data["id"]))',
		),
	),
	'emptyText'=>'Oops, no cells built yet',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>