<?php
/* @var $this TestlabController */
/* @var $model Cell */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'Cell Conditioning',
);

$this->menu=array(
	array('label'=>'Put cells on Formation', 'url'=>array('cellformation')),
	array('label'=>'View Cells on Formation', 'url'=>array('formationindex')),
	array('label'=>'Put cells on CAT', 'url'=>array('cellcat')),
	array('label'=>'View Cells on CAT', 'url'=>array('catindex')),
	array('label'=>'View Cells Conditioning', 'url'=>array('conditioningindex')),
	array('label'=>'Miscellaneous Testing', 'url'=>array('misctesting')),
	array('label'=>'View Miscellaneous Tests', 'url'=>array('miscindex')),
	array('label'=>'Test Reassignments', 'url'=>array('testreassignment')),
	array('label'=>'Move Cells to Storage', 'url'=>array('storage')),
	array('label'=>'Deliver Cells to Assembly', 'url'=>array('deliverforbattery')),
	array('label'=>'View All Tests (Historic)', 'url'=>array('testindex')),
	array('label'=>'View All Cells', 'url'=>array('/cell/index')),
);
?>

<h1>Put Cells on Conditioning Charge</h1>
<p>
*Only cells that have been selected for a battery (even as a spare) not currently on test will be listed. 
If you are just looking to change the test channel then please use the 
<?php echo CHtml::link('Test Reassignment', array('testreassignment')); ?> action. 
Only channels that are not in use and are currently marked as in commission will be available.  
Chambers that are marked out of commission will be listed in red in the drop down.
</p>
<?php
/* ionclude JQuery scripts to allow for autocomplte */
Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl().
        '/jui/css/base/jquery-ui.css'
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'id'=>'cat-form',
)); ?>

<?php echo CHtml::checkBox('singleUser', true)?><span style="margin-left:5px">Assign to Single User</span>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cat-grid',
	'dataProvider'=>$model->searchForAssembly(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header'=>'Selected Cells',
			'name'=>'serial_search',
			'type'=>'raw',
			'value'=>'$data->kit->getFormattedSerial()',
		),
		array(
			'header'=>'Battery',
			'name'=>'battery_search',
			'type'=>'raw',
			'value'=>function($data,$row){
				if($data->battery){
					return $data->battery->getSerialNumber();
				} else {
					return $data->getBatteryAsSpareList();
				}	
			},
		),
		array(
			'header'=>'Cycler',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("cyclers[$data->id]", "", Cycler::forList(),array(
						"prompt"=>"-Cycler-",
						"class"=>"cycler-dropdown",
						"onChange"=>"cycSelected(this)",
			))',
		),
		array(
			'header'=>'Channel',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("channels[$data->id]", "", array(),array(
						"prompt"=>"-N/A-",
						"class"=>"channel-dropdown",
						"onChange"=>"chanSelected(this)",
			))',
		),
		array(
			'header'=>'Chamber',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("chambers[$data->id]", "", Chamber::forList(),array(
						"prompt"=>"-Chamber-",
						"style"=>"width:100px",
						"options"=>Chamber::getTextColor(),
			))',
		),
		array(
			'header' => 'Operator',
			'type' => 'raw',
			'value' => array($this, 'getUserInputTextField'),
//			'value'=>'CHtml::textField("user_name[$data->id]",User::getFullNameProper(Yii::app()->user->id),array(
//				"style"=>"width:150px;",
//				"class"=>"ui-autocomplete-input",
//				"autocomplete"=>"off",'.$disabled.'
//			))',
		),
		array(
			'header' => 'Condition Date',
			'type' => 'raw',
			'value'=>'CHtml::textField("dates[$data->id]",date("Y-m-d",time()),array("style"=>"width:100px;", "class"=>"hasDatePicker"))',	
		),
	),
	'htmlOptions'=>array('width'=>'100%'),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); ?>
</div>
<script>
function reloadGrid(data) {	
	if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
    	try
    	{
    	   var cells = $.parseJSON(data);
    	   var alertString = cells.length+' cells were put on conditioning charge. Serial numbers: \n';
    	   cells.forEach(function(cell) {
    		   alertString += cell.serial + ' on ' + cell.cycler + '-' + cell.channel + '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('cat-grid');
    	}
    	catch(e)
    	{
    		$('#cat-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('testlab/cellcat'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('testlab/ajaxconditioning'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">

jQuery(function($) {
	$(document).on('focus','input', function(e){
		this.select();
	});
	
	$(document).on('keyup', 'input', function(e){
        if(e.which==39)
                    $(this).closest('td').next().find('input').focus();
        else if(e.which==37)
                    $(this).closest('td').prev().find('input').focus();
        else if(e.which==40)
                    $(this).closest('tr').next().find('td:eq('+$(this).closest('td').index()+')').find('input').focus();
        else if(e.which==38)
                    $(this).closest('tr').prev().find('td:eq('+$(this).closest('td').index()+')').find('input').focus();
	});
	
	jQuery(document).on('keydown', '.autocomplete-user-input', function(event) {
		$(this).autocomplete({
			'select': function(event, ui){
				//if single user checkbox set all inputs to selected user
				if ($('#singleUser').prop('checked')){
					$('.user-id-input').attr("value", ui.item.id);
					$('.autocomplete-user-input').val(ui.item.value);
				}else{
					var id = event.target.id.toString().replace("names","ids");
					$("#"+id).attr("value", ui.item.id);
				}
			},
			'source':'/ytpdb/user/ajaxUserSearch'
		});
	});

	jQuery('#submit-button').on('click', function(event) {
		var noneChecked = true;
		$('.errorSummary').remove();
		
		$('input[name="autoId[]"]').each(function () {
	        if (this.checked) {
	            noneChecked = false; 
	        }
		});

		if(noneChecked)
		{
			alert('You must select at least one cell to put on CAT');
			return false;
		}
	});
	
	jQuery('body').on('focus', '.hasDatePicker', function(event) {
		$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
	});
});

function cycSelected(sel)
{
	var id = sel.id.toString().replace("Channel_Status","");
	var cycler_id = $('option:selected', $(sel)).attr("value");

	$.ajax({
		url: '<?php echo $this->createUrl('/cycler/ajaxchannellist'); ?>',
		type: 'POST',
		data: 
		{
			id: cycler_id,
		},
		success: function(data) {
			$('.cycler-dropdown').val(cycler_id);
			
			$('.channel-dropdown').attr('disabled',false);
			$('.channel-dropdown').html(data);
			$('.channel-dropdown').data('prevValue', '');
			$('.channel-dropdown').data('prevText', '');
		},
	});	
}

function chanSelected(sel)
{
	/* remove the selected value from the other dropdowns */
	var el = $(sel);
	var selectedValue = $('option:selected', el).val();
	if(selectedValue!=''){
		$('.channel-dropdown').not(el).each(function(index){
			$('option[value="'+selectedValue+'"]', this).remove();
		});	
	} 
	if (el.data('prevValue')){
		/* add previous value back to selects */
		$('.channel-dropdown').not(el).each(function(index){
			$(this).append($('<option>', {value : el.data('prevValue')})
				.text(el.data('prevText')));
		});	
	}
	el.data('prevValue', sel.value);
	el.data('prevText', sel.options[sel.selectedIndex].text);
}
</script>
