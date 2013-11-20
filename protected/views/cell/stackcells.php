<?php
/* @var $this CellController */
/* @var $model Cell */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	'Stack Cells',
);

$this->menu=array(
	array('label'=>'List Cell', 'url'=>array('index')),
	array('label'=>'Create Cell', 'url'=>array('create')),
	array(
		'label'=>'Download Current', 
		'url'=>array('admin'),
		'linkOptions'=>array(
			'id'=>'csv-download',
		),
	),
);
?>

<h1>Stack Cells</h1>

<?php

if (Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer'))
{
	/* ionclude JQuery scripts to allow for autocomplte */
	Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
	Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl().
        '/jui/css/base/jquery-ui.css'
	);
}

?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'id'=>'stacking-form',
)); ?>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stacking-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header'=>'Unstacked Kits',
			'name'=>'serial_search',
			'type'=>'raw',
			'value'=>'$data->getFormattedSerial()',
		),
		array(
			'name'=>'refnum_search',
			'type'=>'raw',
			'value'=>
					'CHtml::dropDownList("refnumIds[$data->id]",$data->ref_num_id,
							CHtml::listData(RefNum::model()->findAll(),"id", "number"),
							array(
								"empty"=>"-Select Reference-",
								"onChange"=>"refSelected(this)",
							)
			)',
		),
		array(
			'name'=>'eap_num',
			'type'=>'raw',
			'value'=>'CHtml::textField("eaps[$data->id]",$data->eap_num)',
		),
		array(
			'header' => 'Stacker',
			'type' => 'raw',
			'value' => array($this, 'getStackerTextField'),
//			'value'=>'CHtml::textField("user_name[$data->id]",User::getFullNameProper(Yii::app()->user->id),array(
//				"style"=>"width:150px;",
//				"class"=>"ui-autocomplete-input",
//				"autocomplete"=>"off",'.$disabled.'
//			))',
		),
		array(
			'header' => 'Stack Date',
			'type' => 'raw',
			'value'=>'CHtml::textField("dates[$data->id]",date("Y-m-d",time()),array("style"=>"width:100px;", "class"=>"hasDatePicker"))',	
		),
		/*
		'stacker_id',
		'stack_date',
		'dry_wt',
		'wet_wt',
		'filler_id',
		'fill_date',
		'inspector_id',
		'inspection_date',
		*/
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
    $.fn.yiiGridView.update('stacking-grid');
    
    if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
        $('#stacking-form').prepend(data);
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('cell/stackcells'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('cell/ajaxstackcells'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

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

jQuery('.hasDatePicker').datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});

jQuery('#submit-button').bind('click', function(event) {
	$('.errorSummary').remove();
});
function refSelected(sel)
{
	var id = sel.id.toString().replace("refnumIds","eaps");
	var ref = $('option:selected', $(sel)).text();
	if(ref=="-Select Reference-")
	{
		$("#"+id).attr("value","");
	}
	else
	{
		$("#"+id).attr("value","EAP "+ ref + " ADD");
		$("#"+id).focus();
	}
		
	
	
}

</script>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 1; display: none;"></ul>
<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
