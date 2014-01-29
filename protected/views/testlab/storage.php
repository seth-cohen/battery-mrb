<?php
/* @var $this TestlabController */
/* @var $model Cell */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'Put Cells in Storage',
);

$this->menu=array(
	array('label'=>'Put cells on Formation', 'url'=>array('cellformation')),
	array('label'=>'Active Formation', 'url'=>array('formationindex')),
	array('label'=>'Put cells on CAT', 'url'=>array('cellcat')),
	array('label'=>'Active CAT', 'url'=>array('catindex')),
	array('label'=>'Test Reassignments', 'url'=>array('testreassignment')),
	array('label'=>'View All Cells', 'url'=>array('/cell/index')),
);
?>

<h1>Move Cells to Storage</h1>
<p>
*All cells that have been have had the fill port welded but have not been assembled into a battery already (even if currently selected) will be available in the list
below to be put into storage.
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
	'id'=>'storage-form',
)); ?>

<?php echo CHtml::checkBox('singleUser', true)?><span style="margin-left:5px">Assign to Single User</span>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'storage-grid',
	'dataProvider'=>$model->searchForStorage(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header'=>'Cells',
			'name'=>'serial_search',
			'type'=>'raw',
			'value'=>'$data->kit->getFormattedSerial()',
		),
		array(
			'name'=>'refnum_search',
			'type'=>'raw',
			'value'=>'$data->refNum->number',
			'htmlOptions'=>array('width'=>'60'),
		),
		array(
			'name'=>'location',
			'header' => 'Current Location',
			'value' =>'$data->location',
		),
		array(
			'header'=>'Storage Location',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("locations[$data->id]", "", StorageLocation::forList(),array(
						"prompt"=>"-Location-",
						"class"=>"storage-dropdown",
						//"style"=>"width:125px",
			))',
		),
		array(
			'header' => 'Operator',
			'type' => 'raw',
			'value' => array($this, 'getUserInputTextField'),
		),
//		array(
//			'header' => 'Storage Date',
//			'type' => 'raw',
//			'value'=>'CHtml::textField("dates[$data->id]",date("Y-m-d",time()),array("style"=>"width:100px;", "class"=>"hasDatePicker"))',	
//		),
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
    	   var alertString = cells.length+' cells were put into Storage: \n';
    	   cells.forEach(function(cell) {
    		   alertString += cell.serial + ' on ' + cell.location + '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('storage-grid');
    	}
    	catch(e)
    	{
    		$('#storage-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('testlab/storage'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('testlab/ajaxstorage'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">

jQuery(function($) {
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
			alert('You must select at least one cell to move to storage');
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
			$('.cycler-dropdown').val(cycler_id);
			
			$('.channel-dropdown').attr('disabled',false);
			$('.channel-dropdown').html(data);
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
