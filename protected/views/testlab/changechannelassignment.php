<?php
/* @var $this TestLabController */
/* @var $model TestAssignment */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'Change Channel Assignment',
);

$this->menu=array(
	array('label'=>'Put cells on Formation', 'url'=>array('cellformation')),
	array('label'=>'Active Formation', 'url'=>array('formationindex')),
	array('label'=>'Put cells on CAT', 'url'=>array('cellcat')),
	array('label'=>'Active CAT', 'url'=>array('catindex')),
	array('label'=>'Channel Reassignments', 'url'=>array('changechannelassignment')),
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

<h1>Change Channel Assignment</h1>
<p>*All active test assignments will be visible. If the channel is bad also check the "Mark Bad" check box for that row
	and the channel will be set to out of commission. Once channel is repaired it can be set in the channels index. </p>

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
				));
			},
		),
		array(
			'header'=>'New Channel',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("channels[$data->id]", "", array(),array(
						"prompt"=>"-N/A-",
						"class"=>"channel-dropdown",
						"style"=>"width:50px",
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
				));
			},
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
<?php echo CHtml::ajaxSubmitButton('Filter',array('testlab/changechannelassignment'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('testlab/ajaxchannelreassignment'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

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
			
			$('#'+id).attr('disabled',false);
			$('#'+id).html(data);
			$('#'+id).val('select');
		},
	});
}
</script>
