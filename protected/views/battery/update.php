<?php
/* @var $this BatteryController */
/* @var $model Battery */
/* @var $cellDataProvider cellDataProvider */
/* @var $spareOptions  Array */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	 $model->getSerialNumber() =>array('view','id'=>$model->id),
	'Edit',
);

$this->menu=array(
	array('label'=>'View This Battery', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'Assemble Battery', 'url'=>array('assemble')),
	array('label'=>'Accept Test Data', 'url'=>array('accepttestdata'), 'visible'=>Yii::app()->user->checkAccess('quality')),
	array('label'=>'Ship Batteries', 'url'=>array('ship')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
);
?>

<h1>Edit Battery <?php echo $model->getSerialNumber(); ?> Details</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<?php $usageForm=$this->beginWidget('CActiveForm', array(
	'id'=>'usage-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>
<div id="battery-details" class="shadow border"  style="margin-top:10px;">
<?php echo CHtml::button('Show Add Spares', array("id"=>"add-spares-button", "style"=>"float:left;", 'onClick'=>'showAddSpares();')); ?>
<?php echo CHtml::ajaxSubmitButton('Commit Spares Use',array('battery/ajaxusespares','id'=>$model->id), array('success'=>'reloadGrid'), array("id"=>"commit-spares-button", "style"=>"float:right;")); ?>
<?php  $this->renderPartial('_batterycells', array(
		'model'=>$model,
		'cellDataProvider'=>$cellDataProvider,
		'spareOptions'=>$spareOptions,
	), 
	false, 
	false
);?>
</div>

<?php $this->endWidget(); ?>

<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'spares-dialog',
	'options'=>array(
		'title'=>'Add Spares to '.$model->getSerialNumber(), 
		'autoOpen' => false,
		'width'=>500,
		'modal'=>true,
		'buttons'=>array(
			'Submit'=>'js:function(){submitAddSpares();}',
			'Cancel'=>'js:function(){$( this ).dialog( "close" );}',
		),
	),
));?>

	<?php $spareForm=$this->beginWidget('CActiveForm', array(
		'id'=>'spare-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>true,
	)); ?>

	<div class="shadow border" id="cellspares-wrapper" style="margin:auto;;"> 
	<?php echo CHtml::ajaxSubmitButton('Submit',array('battery/ajaxaddspares','id'=>$model->id), array('success'=>'reloadGrid'), array("id"=>"submit-spares-button", "style"=>"float:left;")); ?>
	<div style="text-align:center; width: 100%; font-size:1.2em;">ADD SPARES</div>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>"cellspares-grid",
			'template'=>'{items}',
			'dataProvider'=>$sparesDataProvider,
			'columns'=>array(
				array(
					'name'=>'Cell No.',
					'value'=>'$data["id"]',
				),
				array(
					'header'=>'Cell Serial',
					'type'=>'raw',
					'value'=>function($data, $row) {
						return	CHtml::dropDownList('Battery[Spares]['.$data['id'].'][id]', '', array(),array(
								'prompt'=>'-N/A-',
								'class'=>'cell-dropdown spares',
								'onchange'=>'spareSelected(this)',
								'style'=>'width:150px',
						));
					},
				),
				array(
					'header'=>'Cell Notes',
					'type'=>'html',
					'value'=>function($data, $row) {
						echo '<span id="Notes_' .$data['id'].  '"> Select Cell First </span>';
					}
				),
			),
			'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
			'pager' => array(
				'cssFile' => false,
			)
		)); 
		?>
	</div>

	<?php $this->endWidget(); ?> <!--  ADD SPARES FORM -->
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<script type="text/javascript">
function spareUsed(sel)
{
	/* get the notes element and the cell id */
	var id = $('option:selected', sel).val();
	var notesElement_id = sel.id.toString().replace("Battery_Spares","Notes");
	notesElement_id = notesElement_id.replace('_id','');
	
	/* remove the selected value from the other dropdowns */
	var el = $(sel);
	var selectedValue = $('option:selected', el).val();
	if(selectedValue!=''){
		$('.cell-dropdown').not(el).each(function(index){
			$('option[value="'+selectedValue+'"]', this).remove();
		});	
	} 
	if (el.data('prevValue')){
		/* add previous value back to selects */
		$('.cell-dropdown').not(el).each(function(index){
			$(this).append($('<option>', {value : el.data('prevValue')})
				.text(el.data('prevText')));
		});	
	}
	el.data('prevValue', sel.value);
	el.data('prevText', sel.options[sel.selectedIndex].text);
}

function spareSelected(sel)
{
	/* get the notes element and the cell id */
	var id = $('option:selected', sel).val();
	var notesElement_id = sel.id.toString().replace("Battery_Spares","Notes");
	notesElement_id = notesElement_id.replace('_id','');
	
	/* remove the selected value from the other dropdowns */
	var el = $(sel);
	var selectedValue = $('option:selected', el).val();
	if(selectedValue!=''){
		$('.cell-dropdown').not(el).each(function(index){
			$('option[value="'+selectedValue+'"]', this).remove();
		});	
	} 
	if (el.data('prevValue')){
		/* add previous value back to selects */
		$('.cell-dropdown').not(el).each(function(index){
			$(this).append($('<option>', {value : el.data('prevValue')})
				.text(el.data('prevText')));
		});	
	}
	el.data('prevValue', sel.value);
	el.data('prevText', sel.options[sel.selectedIndex].text);

	$.ajax({
		type:'get',
		url: '<?php echo $this->createUrl('cell/ajaxgetnotes')?>',
		data:
		{
			id: id.toString(),
		},
		success: function(data){
			$('#'+notesElement_id).html(data);
		},
	});
}

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
    	   $.fn.yiiGridView.update('batterycells-grid');
    	}
    	catch(e)
    	{
    		$('#battery-form').prepend(data);
    		console.log(e.message);
    	}
    }
}

function showAddSpares(){

	//unhide the spares selection wrapper
	$('#cellspares-wrapper').show();
	$("#spares-dialog").dialog("open");
	
	// populate the spare cell serial dropdown
	$.ajax({
		type:'get',
		url: '<?php  echo $this->createUrl('battery/ajaxavailablecells'); ?>',
		data:
		{
			type_id: <?php echo $model->batterytype_id; ?>,
			battery_id: <?php echo $model->id; ?>,
			bForSpares: 1,
		},
		success: function(data){
			$('.cell-dropdown.spares').html(data);
		},
	});
}

function submitAddSpares(){
	// populate the spare cell serial dropdown
	$.ajax({
		type:'post',
		url: '<?php  echo $this->createUrl('battery/ajaxaddspares',array('id'=>$model->id)); ?>',
		data:$('#spare-form').serialize(),
		success: function(data){
			$('#spares-dialog').dialog('close');
			reloadGrid(data);
		},
	});
}
</script>