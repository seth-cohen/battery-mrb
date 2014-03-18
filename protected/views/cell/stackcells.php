<?php
/* @var $this CellController */
/* @var $model Kit */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	'Stack Cells',
);

$this->menu=array(
	array('label'=>'Create Kits', 'url'=>array('kit/multicreate')),
	array('label'=>'Inspect Cells', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells', 'url'=>array('multitipoffcells')),
	array('label'=>'Accept CAT Data', 'url'=>array('multiacceptcatdata'), 'visible'=>Yii::app()->user->checkAccess('quality')),
	array('label'=>'View All Cells', 'url'=>array('index')),
	array('label'=>'Cell Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

/* include JQuery scripts to allow for autocomplte */
Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl().
        '/jui/css/base/jquery-ui.css'
);
?>

<h1>Stack Cells</h1>
<p>*Only kits that have not yet been stacked will be visible in this list.</p>


<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'id'=>'stacking-form',
)); ?>

<?php echo CHtml::checkBox('singleUser', true)?><span style="margin-left:5px">Assign to Single User</span>

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
							CHtml::listData(RefNum::model()->findAll(array("condition"=>"id <> 70")),"id", "number"),
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
			'value' => array($this, 'getUserInputTextField'),
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
    if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
    	try
    	{
    	   var cells = $.parseJSON(data);
    	   var alertString = cells.length+' cells were stacked. Serial numbers: \n';
    	   cells.forEach(function(cell) {
    		   alertString += cell.serial + '-' + cell.stacker + '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('stacking-grid');
    	}
    	catch(e)
    	{
    		$('#stacking-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('cell/multistackcells'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('cell/ajaxstackcells'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

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
			alert('You must select at least one cell to stack');
		}
	});
	
	jQuery('body').on('focus', '.hasDatePicker', function(event) {
		$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
	});
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
