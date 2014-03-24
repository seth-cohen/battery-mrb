<?php
/* @var $this TestLabController */
/* @var $model TestAssignment */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'Change Channel Assignment',
);

$this->menu=array(
	array('label'=>'Put cells on Formation', 'url'=>array('cellformation')),
	array('label'=>'View Cells on Formation', 'url'=>array('formationindex')),
	array('label'=>'Put cells on CAT', 'url'=>array('cellcat')),
	array('label'=>'View Cells on CAT', 'url'=>array('catindex')),
	array('label'=>'Condition for Assembly', 'url'=>array('cellconditioning')),
	array('label'=>'View Cells Conditioning', 'url'=>array('conditioningindex')),
	array('label'=>'Miscellaneous Testing', 'url'=>array('misctesting')),
	array('label'=>'View Miscellaneous Tests', 'url'=>array('miscindex')),
	array('label'=>'Move Cells to Storage', 'url'=>array('storage')),
	array('label'=>'Deliver Cells to Assembly', 'url'=>array('deliverforbattery')),
	array('label'=>'View All Tests (Historic)', 'url'=>array('testindex')),
	array('label'=>'View All Cells', 'url'=>array('/cell/index')),
);


$cyclerList = Cycler::forList();
$chamberList = Chamber::forList();

/* ionclude JQuery scripts to allow for autocomplte */
Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl().
        '/jui/css/base/jquery-ui.css'
);
?>

<h1>Change Test Assignment</h1>
<p>*All active test assignments will be visible. If the channel is bad also check the "Mark Bad" check box for that row
and the channel will be set to out of commission. Once channel is repaired it can be set in the 
<?php echo CHtml::link('Channel Index', array('channel/index')); ?> action.  Cells can also be marked as out of commission 
in the channel index action.
<br/><br/>
If the Chamber is in red then it has been marked out of commission and cells should be moved to a different chamber.
</p>

<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'id'=>'channelassignment-form',
)); ?>

<?php echo CHtml::checkBox('singleUser', true)?><span style="margin-left:5px">Assign to Single User</span>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'channelassignment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'afterAjaxUpdate'=>'function(id,data){
		$(".cycler-dropdown").each(function(index){
    			cycSelected(this);
    		});
    	$("#channelassignment-grid .filters").children(":nth-child(2)").text("Mark Bad");
    	
    }',
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header' => 'Mark Bad',
            'id'=>'badId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'name'=>'serial_search',
			'value'=>'$data->cell->kit->getFormattedSerial()',
		),
		array(
			'name'=>'chamber_search',
			'value'=>'$data->chamber->name',
			'cssClassExpression'=>function($row, $data){
				if($data->chamber->in_commission == 0){
					return 'red-text';
				}
			}
		),
		array(
			'name'=>'cycler_search',
			'value'=>'$data->channel->cycler->name." {".$data->channel->number."}"',
		),
		array(
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'display:none'),
			'headerHtmlOptions'=>array('style'=>'display:none'),
			'value'=>function($data,$row){
				return 
					CHtml::hiddenField("cell_ids[$data->id]", $data->cell_id) .
					CHtml::hiddenField("is_formation[$data->id]", $data->is_formation);
			},
		),
		array(
			'header'=>'New Cycler',
			'type'=>'raw',
			'value'=>function($data,$row) use ($cyclerList){
				return CHtml::dropDownList('cyclers['.$data->id.']', $data->channel->cycler->id, $cyclerList, array(
						"prompt"=>"-Cycler-",
						"class"=>"cycler-dropdown",
						"onChange"=>"cycSelected(this)",
						"style"=>"width:100px",
						"data-original"=>$data->channel->cycler->id,
				));
			},
		),
		array(
			'header'=>'New Channel',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("channels[$data->id]", "", array(),array(
						"prompt"=>"-N/A-",
						"class"=>"channel-dropdown",
						"onChange"=>"channelSelected(this)",
						"style"=>"width:50px",
						"data-channel-id"=>$data->channel->id,
						"data-channel-number"=>$data->channel->number,
			))',
		),
		array(
			'header'=>'New Chamber',
			'type'=>'raw',
			'value'=>function($data,$row) use ($chamberList){
				return CHtml::dropDownList('chambers['.$data->id.']', $data->chamber->id, $chamberList, array(
						"prompt"=>"-Chamber-",
						"class"=>"chamber-dropdown",
						"style"=>"width:90px",
						'options'=>Chamber::getTextColor(),
				));
			},
			'cssClassExpression'=>function($row, $data){
				if($data->chamber->in_commission == 0){
					return 'red-text';
				}
			}
		),
		array(
			'header' => 'Operator',
			'type' => 'raw',
			'value' => array($this, 'getUserInputTextField'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/cell/view", array("id"=>$data->cell->id))',
			'updateButtonUrl'=>'Yii::app()->createUrl("/cell/update", array("id"=>$data->cell->id))',
		),
		
	),
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
    	   var alertString = cells.length+' cells were reassigned. Serial numbers: \n';
    	   cells.forEach(function(cell) {
    		   alertString += cell.serial + '- From:' + cell.ogCycler + '{' + cell.ogChannel + '} To:' + cell.cycler + '{' + cell.channel + '}\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('channelassignment-grid');
    	}
    	catch(e)
    	{
    		$('#channelassignment-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('testlab/testreassignment'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('testlab/ajaxtestreassignment'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script>
jQuery(function($) {

	$('#channelassignment-grid .filters').attr('align','center');
	/*$('#channelassignment-grid .filters').children(':nth-child(1)').text('Change');*/
	$('#channelassignment-grid .filters').children(':nth-child(2)').text('Mark Bad');

	$('.cycler-dropdown').each(function(index){
		cycSelected(this);
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
			alert('You must select at least one cell to reassign channels');
			return false;
		}
	});

	jQuery('body').on('focus', '.hasDatePicker', function(event) {
		$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
	});
});


function channelSelected(sel)
{
	/* remove the selected value from the other dropdowns */
	var el = $(sel);
	var selectedValue = $('option:selected', el).val();
	var changed_cycler_id = sel.id.toString().replace("channels","cyclers");

	/* get the cycler value for the changed channel */
	var changed_cycler = document.getElementById(changed_cycler_id).value;
	
	if(selectedValue!=''){
		$('.channel-dropdown').not(el).each(function(index){
			/* Check if the cycler is the same  and only remove the option if the same */
			var cycler_id = this.id.toString().replace("channels","cyclers");
			var cycler = document.getElementById(cycler_id).value;
			
			if(cycler == changed_cycler){
				$('option[value="'+selectedValue+'"]', this).remove();
			}
		});	
	} 
	if (el.data('prevValue')){
		/* add previous value back to selects */
		$('.channel-dropdown').not(el).each(function(index){
			/* Check if the cycler is the same  and only add the option if the same */
			var cycler_id = this.id.toString().replace("channels","cyclers");
			var cycler = document.getElementById(cycler_id).value;

			if(cycler == changed_cycler){
				$(this).append($('<option>', {value : el.data('prevValue')})
					.text(el.data('prevText')));
			}
		});	
	}
	el.data('prevValue', sel.value);
	el.data('prevText', sel.options[sel.selectedIndex].text);
}

function cycSelected(sel)
{
	var id = sel.id.toString().replace("cyclers","channels");
	var cycler_id = $('option:selected', $(sel)).attr("value");

	$.ajax({
		url: '<?php echo $this->createUrl('/cycler/ajaxchannellist'); ?>',
		type: 'POST',
		data: 
		{
			id: cycler_id,
		},
		success: function(data) {
			/* set all following test assignments to the same channel */
			//$('.cycler-dropdown').val(cycler_id);
			var el = $('#'+id);
			var selected = document.getElementById(id);
			
			el.attr('disabled',false);
			el.html(data);

			if($(sel).val() == $(sel).data('original')){
				$('option[value=""]', el).remove();
				el.prepend($('<option>', {value: el.data('channel-id')})
						.text(el.data('channel-number')));
				el.val(el.data('channel-id'));
				
				el.data('prevValue', selected.value);
				el.data('prevText', selected.options[selected.selectedIndex].text);
			}
		},
	});
}
</script>
