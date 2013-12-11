<?php
/* @var $this BatteryController */
/* @var $batterytypeModel Batterytype */
/* @var $cellDataProviders  CArrayDataProvider[] */

?>

<?php 
$imageUrl = CHtml::image(Yii::app()->baseUrl.'/css/left.png', 'Previous', array('style'=>'float:left;margin-left:50px;top:130px;position:relative;'));
echo CHtml::link($imageUrl,'#',array('id'=>'previous-module-link' )); 

$imageUrl = CHtml::image(Yii::app()->baseUrl.'/css/right.png', 'Next', array('style'=>'float:right;margin-right:50px;top:130px;position:relative;'));
echo CHtml::link($imageUrl,'#',array('id'=>'next-module-link')) 
?>

<?php 
$x = 0;
foreach($cellDataProviders as $cellDataProvider): 
	$cellDataProvider->pagination->currentPage = $x;
?>
<div class="shadow border" id="cellselection-wrapper-<?=$x?>" 
	style="width:300px;margin:10px auto;position:absolute;<?=$x>0?'top:0px;right:-350px;':'right:204px;';?>">

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>"cellselection-grid-$x",
	'template'=>'{summary}{items}',
	'dataProvider'=>$cellDataProvider,
	'columns'=>array(
		array(
			'name'=>'Cell No.',
			'value'=>'$data["id"]',
		),
		array(
			'name'=>'Value',
			'value'=>'$data["value"]',
		),
		array(
			'header'=>'Cell Serial',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("Battery[Cells][$data[id]]", "", Cycler::forList(),array(
						"prompt"=>"-Cycler-",
						"class"=>"cycler-dropdown",
						"onChange"=>"cycSelected(this)",
						"style"=>"width:100px",
			))',
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