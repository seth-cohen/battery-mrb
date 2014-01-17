<?php
/* @var $this BatteryController */
/* @var $batterytypeModel Batterytype */
/* @var $cellDataProviders  CArrayDataProvider[] */

$controller = $this;
?>

<h2 style="text-align:center;"><?php echo $batterytypeModel->name; ?></h2>

<?php echo CHtml::hiddenField("num_cells",$batterytypeModel->num_cells); ?>

<?php 
$imageUrl = CHtml::image(Yii::app()->baseUrl.'/css/left.png', 'Previous', array('style'=>'float:left;margin-left:50px;top:130px;position:relative;'));
echo CHtml::link($imageUrl,'#',array('id'=>'previous-module-link' )); 

$imageUrl = CHtml::image(Yii::app()->baseUrl.'/css/right.png', 'Next', array('style'=>'float:right;margin-right:50px;top:130px;position:relative;'));
echo CHtml::link($imageUrl,'#',array('id'=>'next-module-link')); 

$x = 0;
foreach($cellDataProviders as $cellDataProvider): 
	$cellDataProvider->pagination->currentPage = $x;
?>

<div class="shadow border" id="cellselection-wrapper-<?=$x?>" 
	style="width:300px;margin:10px auto;position:absolute;<?=$x>0?'top:25px;right:-350px;':'right:204px;';?>">

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>"cellselection-grid-$x",
	'template'=>'{summary}{items}',
	'summaryText'=>'Choose Cells {start}-{end} of the {count} total',
	'dataProvider'=>$cellDataProvider,
	'columns'=>array(
		array(
			'name'=>'Cell No.',
			'value'=>'$data["id"]',
		),
		array(
			'header'=>'Cell Serial',
			'type'=>'raw',
			'value'=>function($data, $row) use ($controller) {
				return	CHtml::dropDownList('Battery[Cells]['.$data['id'].']', '', array(),array(
						'prompt'=>'-N/A-',
						'class'=>'cell-dropdown cells',
						'onchange'=>'cellSelected(this)',
						'style'=>'width:150px',
				));
			},
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>
</div>
<div class="clear"></div>
<?php $x+=1; endforeach; ?>


