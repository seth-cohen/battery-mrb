<?php
/* @var $this BatteryController */
/* @var $batteryModel Battery */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Battery'=>array('index'),
	'Assemble Battery',
);

$this->menu=array(
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
);

/* include JQuery scripts to allow for autocomplte */
Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl().
        '/jui/css/base/jquery-ui.css'
);
?>

<h1>Assemble Battery</h1>
<p>*Only batteries that have had cell selections will be available for assembly..</p>


<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>false, // no need for this.
	'enableClientValidation'=>true,
	'id'=>'stacking-form',
)); ?>

<div class="form">
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php echo $form->errorSummary($batteryModel); ?>
	
	<div class="left-form">
		<div class="row">
	        <?php echo $form->labelEx($batteryModel,'batterytype_id'); ?>
	        <?php echo $form->dropDownList($batteryModel, 'batterytype_id', 
								CHtml::listData(Batterytype::model()->findAll(), 'id','name'), 
								array(
									'prompt'=>' -Select Type.- ',
									'onchange'=>'typeSelected(this)',
									'style'=>'width:152px',
									'options'=>Batterytype::getIdPartNums(),
								)); ?> <span id='part-num' style='margin-left:5px;'></span>
	        <?php echo $form->error($batteryModel,'batterytype_id'); ?>
	    </div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($batteryModel,'serial_num'); ?>
			<?php echo $form->dropDownList( $batteryModel,'serial_num', array(),
								array(
									'prompt'=>' -N/A.- ',
									'onchange'=>'serialSelected(this)',
									'style'=>'width:152px',
								)); ?>
			<?php echo $form->error($batteryModel,'serial_num'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
</div>
<?php $this->endWidget(); ?>

<div id="batterycell-details" style="overflow-x:hidden; position:relative;margin-top: 12px;"></div>

<script type="text/javascript">
function typeSelected(sel, urlSerialsToAssemble){
	var type_id = $('option:selected', $(sel)).val();
	var partNum = $('option:selected', $(sel)).data('partnum');

	if (!partNum) partNum = '-N/A-';
	$('#part-num').text('('+partNum+')');

	// populate the battery serial dropdown
	$.ajax({
		type:'get',
		url: '<?php echo $this->createUrl('battery/ajaxserialsforassembly'); ?>',
		data:
		{
			type_id: type_id.toString(),
		},
		success: function(data){
			$('#Battery_serial_num').html(data);
		},
	});
}

function serialSelected(sel){
	var battery_id = $('option:selected', $(sel)).val();

	// populate the battery serial dropdown
	$.ajax({
		type:'get',
		url: '<?php echo $this->createUrl('battery/ajaxgetbatterycells'); ?>',
		data:
		{
			id: battery_id.toString(),
		},
		success: function(data){
			$('#batterycell-details').html(data);
		},
	});
}

</script>
