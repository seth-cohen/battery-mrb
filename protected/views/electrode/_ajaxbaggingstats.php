<?php 
/* @var $this ElectrodeController */
/* @var $model Electrode */
/* @var $bagginngProvider CActiveDataProvider */
?>

<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/styles.css'); ?>

<?php 
if ($this->action->id != 'view')
{
	Yii::app()->clientScript->scriptMap=array(
                    'jquery.yiigridview.js'=>false
                ); 
}

?>

<h2 style="width:100%; text-align:center">Bagging Stats for <?php echo $model->is_anode?'Anode':'Cathode'; ?> Lot <?php echo $model->lot_num; ?>  </h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bagging-grid',
	'dataProvider'=>$baggingProvider,
	'columns'=>array(
		'bagging_date',
		'reject_count',
		'good_count',
		array(
        	'name'=>'Operator',
        	'value'=>function($data,$row){
					return ($data->bagger==null)?'N/A':$data->bagger->getFullName();
        	},
        ),
	),
	'emptyText'=>'Oops, no bagged Electrodes Yet',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>