<?php
/* @var $this NcrController */
/* @var $model Ncr */
/* @var $ncrCellDataProvider CActiveDataProvider */
/* @var $ncrCell NcrCell */

$this->breadcrumbs=array(
	'Ncrs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Edit This NCR', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Put Cells on NCR', 'url'=>array('putcellsonncr')),
	array('label'=>'Dispo Cells on NCR', 'url'=>array('dispositioncells')),
	array('label'=>'View All NCRs', 'url'=>array('index')),
	array('label'=>'NCR Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>View NCR-<?php echo $model->number; ?></h1>

<div class="shadow border">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'number',
		'date',
		array(
			'label'=>'Number of Cells on NCR',
			'value'=>count($model->cells),
		),
		array(
			'label'=>'Open Cells on NCR',
			'value'=>count($model->openCells),
		),
	),
	'cssFile'=>Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>

<div class="shadow border">
<h2 style="text-align:center">Cells on NCR-<?php echo $model->number; ?></h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'channel-grid',
	'dataProvider'=>$ncrCellDataProvider,
	'filter'=>$ncrCell,
	'columns'=>array(
		array(
			'name'=>'ncr_search',
			'value'=>'"NCR-".$data->ncr->number',
		),
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
			'name'=>'refnum_search',
			'value'=>'$data->cell->refNum->number',
		),
		'disposition_string',
	),		
	'emptyText'=>'Oops, no cells on this NCR',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>
</div>
