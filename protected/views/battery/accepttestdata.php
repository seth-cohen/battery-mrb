<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Battery'=>array('/battery'),
	'Accept Test Data',
);

$this->menu=array(
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'Assemble Battery', 'url'=>array('assemble')),
	array('label'=>'Ship Batteries', 'url'=>array('ship')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Accept Battery Test Data</h1>
<p>
*Only batteries that have been assembled will be visible. 
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
	'id'=>'accept-form',
)); ?>

<?php echo CHtml::checkBox('singleUser', true)?><span style="margin-left:5px">Assign to Single User</span>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'accept-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header'=>'Assembled Batteries',
			'type'=>'raw',
			'value'=>'$data->getFormattedSerial()',
		),
		array(
			'header'=>'Battery Type',
			'name'=>'batterytype_search',
			'value'=>'$data->batterytype->name',
		),
		array(
			'name'=>'refnum_search',
			'type'=>'raw',
			'value'=>'$data->refNum->number',
			'htmlOptions'=>array('width'=>'60'),
		),
		array(
			'header' => 'Assembler',
			'name' => 'assembler_search',
			'value' =>'$data->assembler->getFullName()',
		),
		array(
			'header' => 'Acceptance Date',
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
    	   var batteries = $.parseJSON(data);
    	   var alertString = batteries.length+' batteries were Accepted. Serial numbers: \n';
    	   batteries.forEach(function(battery) {
    		   alertString += battery.serial +  '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('accept-grid');
    	}
    	catch(e)
    	{
    		$('#accept-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('battery/accepttestdata'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('battery/ajaxaccepttestdata'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

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
			alert('You must select at least one battery to accept test data');
			return false;
		}
	});
	
	jQuery('body').on('focus', '.hasDatePicker', function(event) {
		$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
	});
});
</script>
