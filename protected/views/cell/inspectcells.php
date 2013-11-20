<?php
/* @var $this CellController */
/* @var $model Cell */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	'Fill Cells (multi)',
);

$this->menu=array(
	array('label'=>'Create New Kit', 'url'=>array('kit/create')),
	array('label'=>'Stack Cell (single)', 'url'=>array('stackcell')),
	array('label'=>'Stack Cells (multi)', 'url'=>array('multistackcells')),
	array('label'=>'Fill Cell (single)', 'url'=>array('fillcell')),
	array('label'=>'Fill Cells (multi)', 'url'=>array('multifillcells')),
	array('label'=>'Inspect Cell (single)', 'url'=>array('inspectcell')),
	array('label'=>'Inspect Cells (multi)', 'url'=>array('multiinspectcells')),
	array('label'=>'View All Cells', 'url'=>array('index')),
);
?>

<h1>Inspect Cells (Multi)</h1>

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
	'id'=>'inspecting-form',
)); ?>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inspecting-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header'=>'Inspect Cells',
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
			'header' => 'Inspector',
			'type' => 'raw',
			'value' => array($this, 'getUserInputTextField'),
//			'value'=>'CHtml::textField("user_name[$data->id]",User::getFullNameProper(Yii::app()->user->id),array(
//				"style"=>"width:150px;",
//				"class"=>"ui-autocomplete-input",
//				"autocomplete"=>"off",'.$disabled.'
//			))',
		),
		array(
			'header' => 'Inspection Date',
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
    $.fn.yiiGridView.update('inspecting-grid');
    
    if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
        $('#inspecting-form').prepend(data);
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('cell/multiinspectcells'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('cell/ajaxinspectcells'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

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
		alert('You must select at least one cell to inspect');
	}
});

</script>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 1; display: none;"></ul>
<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
