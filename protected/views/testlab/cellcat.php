<?php
/* @var $this CellController */
/* @var $model FormationDetail */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'Cell CAT',
);

$this->menu=array(
	array('label'=>'Put cells on Formation', 'url'=>array('cellformation')),
	array('label'=>'Active Formation', 'url'=>array('formationindex')),
	array('label'=>'Put cells on CAT', 'url'=>array('cellcat')),
	array('label'=>'Active CAT', 'url'=>array('catindex')),
	array('label'=>'Channel Reassignments', 'url'=>array('changechannelassignment')),
	array('label'=>'View All Cells', 'url'=>array('/cell/index')),
);
?>

<h1>Put Cells on CAT</h1>
<p>
*Only cells that have been put on formation and are not currently on test will be listed. 
If the cell you are looking for is currently on test please use the 
<?php echo CHtml::link('Change Test Assignment', array('changetestassignment')); ?> action.
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

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cat-grid',
	'dataProvider'=>$model->searchFormed(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header'=>'Formed Cells',
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
			'header'=>'Cycler',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("cyclers[$data->id]", "", Cycler::forList(),array(
						"prompt"=>"-Cycler-",
						"class"=>"cycler-dropdown",
						"onChange"=>"cycSelected(this)",
						"style"=>"width:100px",
			))',
		),
		array(
			'header'=>'Channel',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("channels[$data->id]", "", array(),array(
						"prompt"=>"-N/A-",
						"class"=>"channel-dropdown",
			))',
		),
		array(
			'header'=>'Chamber',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("chambers[$data->id]", "", Chamber::forList(),array(
						"prompt"=>"-Chamber-",
						"style"=>"width:100px",
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
			'header' => 'CAT Date',
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
    	   var alertString = cells.length+' cells were put on CAT. Serial numbers: \n';
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
<?php echo CHtml::ajaxSubmitButton('Submit',array('testlab/ajaxcat'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">

jQuery(function($) {
jQuery('.ui-autocomplete-input').live('keydown', function(event) {
	$(this).autocomplete({
			'select': function(event, ui){
				
				var id = event.target.id.toString().replace("names","ids");
				$('.user-id-input').attr("value", ui.item.id);
				$('.ui-autocomplete-input').val(ui.item.value);
				$('.ui-autocomplete-input').val(ui.item.value);
			},
			'source':'/ytpdb/user/ajaxUserSearch'
		});
	});
});

jQuery('.hasDatePicker').live('focus', function(event) {
	$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
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

jQuery('#submit-button').bind('click', function(event) {
	var noneChecked = true;
	$('.errorSummary').remove();
	
	$('input[type=checkbox]').each(function () {
        if (this.checked) {
            noneChecked = false; 
        }
	});

	if(noneChecked)
	{
		alert('You must select at least one cell to fill');
	}
});

</script>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 1; display: none;"></ul>
<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
