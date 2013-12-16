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
	array('label'=>'Stack Cells (multi)', 'url'=>array('multistackcells')),
	array('label'=>'Inspect Cells (multi)', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells (multi)', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells (multi)', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells (multi)', 'url'=>array('multitipoffcells')),
	array('label'=>'View All Cells', 'url'=>array('index')),
);
?>

<h1>Fill Cells (Multi)</h1>
<p>*Only cells that have had covers laser welded on but haven't yet been filled will be visible in this list.</p>
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
	'id'=>'filling-form',
)); ?>

<?php echo CHtml::checkBox('singleUser', true)?><span style="margin-left:5px">Assign to Single User</span>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'filling-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'header'=>'Unfilled Cells',
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
			'header' => 'Filler',
			'type' => 'raw',
			'value' => array($this, 'getUserInputTextField'),
//			'value'=>'CHtml::textField("user_name[$data->id]",User::getFullNameProper(Yii::app()->user->id),array(
//				"style"=>"width:150px;",
//				"class"=>"ui-autocomplete-input",
//				"autocomplete"=>"off",'.$disabled.'
//			))',
		),
		array(
			'header' => 'Fill Date',
			'type' => 'raw',
			'value'=>'CHtml::textField("dates[$data->id]",date("Y-m-d",time()),array("style"=>"width:100px;", "class"=>"hasDatePicker"))',	
		),
		array(
			'header' => 'Dry Wt(g)',
			'type' => 'raw',
			'value'=>'CHtml::textField("dry_wts[$data->id]","",array("style"=>"width:60px;"))',	
		),
		array(
			'header' => 'Wet Wt(g)',
			'type' => 'raw',
			'value'=>'CHtml::textField("wet_wts[$data->id]","",array("style"=>"width:60px;"))',	
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
    	   var alertString = cells.length+' cells were filled. Serial numbers: \n';
    	   cells.forEach(function(cell) {
    		   alertString += cell.serial + ' <> ' + cell.filler + '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('filling-grid');
    	}
    	catch(e)
    	{
    		$('#filling-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('cell/multifillcells'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('cell/ajaxfillcells'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">

jQuery(function($) {

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

	jQuery('body').on('focus', '.hasDatePicker', function(event) {
		$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
	});
});

</script>
