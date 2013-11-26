<?php
/* @var $this CellController */
/* @var $model Cell */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	'Laser Weld Cells (multi)',
);

$this->menu=array(
	array('label'=>'Create New Kit', 'url'=>array('kit/create')),
	array('label'=>'Stack Cells (multi)', 'url'=>array('multistackcells')),
	array('label'=>'Inspect Cells (multi)', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells (multi)', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells (multi)', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells (multi)', 'url'=>array('multitipoffcells')),
	array('label'=>'View All Cells', 'url'=>array('index')),
);
?>

<h1>Fill Port Weld Cells (Multi)</h1>

<p>*Only cells that have been filled and not yet had the fill port welded will be visible.
	Regardless of whether or not they have been put on formation yet.</p>
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
	'id'=>'portwelding-form',
)); ?>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'portwelding-grid',
	'dataProvider'=>$model->searchAtForm(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header'=>'Cells at Formation',
			'name'=>'serial_search',
			'type'=>'raw',
			'value'=>'$data->kit->getFormattedSerial()',
		),
		array(
			'name'=>'refnum_search',
			'type'=>'raw',
			'value'=>'$data->refNum->number',
		),
		'eap_num',
		array(
			'header' => 'Fill Port Welder',
			'type' => 'raw',
			'value' => array($this, 'getUserInputTextField'),
//			'value'=>'CHtml::textField("user_name[$data->id]",User::getFullNameProper(Yii::app()->user->id),array(
//				"style"=>"width:150px;",
//				"class"=>"ui-autocomplete-input",
//				"autocomplete"=>"off",'.$disabled.'
//			))',
		),
		array(
			'header' => 'Port Weld Date',
			'type' => 'raw',
			'value'=>'CHtml::textField("dates[$data->id]",date("Y-m-d",time()),array("style"=>"width:100px;", "class"=>"hasDatePicker"))',	
		),
	),
	//'htmlOptions'=>array('class'=>'shadow grid-view'),
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
    	   var alertString = cells.length+' cells were tipped off. Serial numbers: \n';
    	   cells.forEach(function(cell) {
    		   alertString += cell.serial + ' <> ' + cell.portwelder + '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('portwelding-grid');
    	}
    	catch(e)
    	{
    		$('#portwelding-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('cell/multitipoffcells'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('cell/ajaxtipoffcells'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">

jQuery(function($) {
jQuery('.ui-autocomplete-input').live('keydown', function(event) {
	$(this).autocomplete({
			'select': function(event, ui){
				var id = event.target.id.toString().replace("names","ids");
				$("#"+id).attr("value", ui.item.id);
			},
			'source':'/ytpdb/user/ajaxUserSearch'
		});
	});
});

jQuery('.hasDatePicker').live('focus', function(event) {
	$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
});


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
		alert('You must select at least one cell to fillport weld');
	}
});

</script>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 1; display: none;"></ul>
<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
