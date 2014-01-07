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

Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.easing.1.3.js');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.easing.compatibility.js');
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
var currentPage = 0;

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
		url: '<?php echo $this->createUrl('battery/ajaxcellsforbatteryassembly'); ?>',
		data:
		{
			id: battery_id.toString(),
		},
		success: function(data){
			$('#batterycell-details').html(data).css('height','400px');
			$('#previous-module-link').hide();
		},
	});
}

$(document).on('click', '#next-module-link', function(event){
	if (!$('#cellselection-wrapper-'+(currentPage+1)).length){
		//do nothing
	} else {
		if (!$('#cellselection-wrapper-'+(currentPage+2)).length){
			//do nothing
			$('#next-module-link').hide();
		}
		if(currentPage == 0)
			$('#previous-module-link').show();
		
		//animate current grid left
		var $element = $('#cellselection-wrapper-'+currentPage);
		var right = $element.parent().width()+20;
		$element.animate({
			right: right,
		},{
			easing: 'easeInExpo',
		});
		currentPage += 1;
		
		//animate next grid left to center
		var right = $element.parent().width()/2-$element.width()/2;
		$element = $('#cellselection-wrapper-'+currentPage);
		$element.animate({
			right: right,
		},{
			duration: 600,
			easing: 'easeOutBounce',
		});
	}
	return false;
});

$(document).on('click', '#previous-module-link', function(event){
	if (!$('#cellselection-wrapper-'+(currentPage-1)).length){
		//do nothing
		$('#previous-module-link').hide();
	} else {
		if (!$('#cellselection-wrapper-'+(currentPage-2)).length){
			//do nothing
			$('#previous-module-link').hide();
		}
		if(!$('#cellselection-wrapper-'+(currentPage+1)).length)
			$('#next-module-link').show();
		
		//animate current grid left
		var $element = $('#cellselection-wrapper-'+currentPage);
		var right = -$element.parent().width()-20;
		$element.animate({
			right: right,
		},{
			easing: 'easeInExpo',
		});
		currentPage -= 1;
		
		//animate next grid right to center
		var right = $element.parent().width()/2-$element.width()/2;
		$element = $('#cellselection-wrapper-'+currentPage);
		$element.animate({
			right: right,
		},{
			duration: 600,
			easing: 'easeOutBounce',
		});
	}
	return false;
});

</script>
