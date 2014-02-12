<?php 
/* @var $ncrCellDataProvider CActiveDataProvider */
/* @var $ncrCell NcrCell */
?>

<h2 style="text-align:center">Cells on NCR-<?php echo $ncrCell->ncr->number; ?></h2>
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