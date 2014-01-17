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
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($batteryModel,'assembler_search'); ?>
			<?php $user_id = ''; ?>
			<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
				<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array( 
					'model'=>$batteryModel,
				 	'attribute'=>'assembler_search',
					'sourceUrl'=>$this->createUrl('/user/ajaxUserSearch'),
					'options' => array(
						'select'=>'js: 
							function(event, ui){
								$("#Battery_assembler_id").attr("value", ui.item.id);
							}',
					),
					'htmlOptions'=>array(
						'style'=>'width:152px;',
					),
				)); ?>
			<?php else: /* user can only create it as themselves */ 
				$user_id = Yii::app()->user->id; ?>
				<?php echo CHtml::textField('assembler_search',User::getFullNameProper($user_id), array(
							'disabled'=>true,
							'style'=>'width:152px;'
				));?>
			<?php endif; ?>
			
			<?php echo $form->error($batteryModel,'assembler_search'); ?>
			<?php echo $form->hiddenField($batteryModel, 'assembler_id', array('value'=>$user_id)); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($batteryModel,'assembly_date'); ?>
			<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$batteryModel,
			       'name'=>'assembly_date',
			    	'value'=>date("Y-m-d",time()),
			        // additional javascript options for the date picker plugin
			        'options'=>array(
			            'showAnim'=>'slideDown',
			            'changeMonth'=>true,
			            'changeYear'=>true,
			            'dateFormat' => 'yy-mm-dd',
			        ),
			        'htmlOptions'=>array(
						'style'=>'width:150px;',
					),
			    ));
			?>
			<?php echo $form->error($batteryModel,'assembly_date'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<?php echo CHtml::ajaxSubmitButton('Filter',array('battery/assemble'), array(),array("style"=>"display:none;")); ?>
	<?php echo CHtml::ajaxSubmitButton('Submit',array('battery/ajaxassemble'), array('success'=>'assembleComplete'), array("id"=>"submit-button")); ?>
	
	
</div>

<div id="batterycell-details" style="overflow-x:hidden; position:relative;margin-top: 12px;"></div>

<?php $this->endWidget(); ?>
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
			$('#batterycell-details').html(data).css('height','460px');
			$('#previous-module-link').hide();

			$('.grid-view').each( function (event) {
				console.log(event);
				$('.grid-view .filters').attr('align','center');
				$('.grid-view .filters').children(':nth-child(1)').text('Use Spare');
			});
		},
	});
}

function cellSelected(sel)
{
	/* remove the selected value from the other dropdowns */
	var el = $(sel);
	var selectedValue = $('option:selected', el).val();
	if(selectedValue!=''){
		$('.cell-dropdown').not(el).each(function(index){
			$('option[value="'+selectedValue+'"]', this).remove();
		});	
	} 
	if (el.data('prevValue')){
		/* add previous value back to selects */
		$('.cell-dropdown').not(el).each(function(index){
			$(this).append($('<option>', {value : el.data('prevValue')})
				.text(el.data('prevText')));
		});	
	}
	el.data('prevValue', sel.value);
	el.data('prevText', sel.options[sel.selectedIndex].text);
}

function assembleComplete(data){
	if(data=='hide')
	{
		$('.errorSummary').remove();
	}
	else
	{
		try
		{
		   var batteryResult = $.parseJSON(data);
		   var alertString = 'You selected cells for ' + batteryResult.batterytype + ' SN: ' + batteryResult.serial_num;
		   alertString += '\n' + batteryResult.num_spares + ' spares were selected.\n\nWould you like to select another battery?';
		   if(confirm(alertString)==false){
				window.location  = urlSuccess;
			} else {
				 window.location.reload();
			}
		}
		catch(e)
		{
			$('#battery-form').prepend(data);
			console.log(e.message);
		}
	}
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
