<?php
/* @var $this CellController */
/* @var $kitDataProvider CArrayDataProvider */
/* @var $cellModel Cell */
/* @var $kitModel Kit */


$this->breadcrumbs=array(
	'Cells'=>array('index'),
	'Create Generic Cells',
);

$this->menu=array(
	array('label'=>'Create Kits', 'url'=>array('kit/multicreate')),
	array('label'=>'Stack Cells', 'url'=>array('multstackcells')),
	array('label'=>'Cover Attachment', 'url'=>array('multicoverattachcells')),
	array('label'=>'Inspect Cells', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells', 'url'=>array('multitipoffcells')),
	array('label'=>'Accept CAT Data', 'url'=>array('multiacceptcatdata'), 'visible'=>Yii::app()->user->checkAccess('quality')),
	array('label'=>'View All Cells', 'url'=>array('index')),
	array('label'=>'Cell Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Create Generic Cells</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'id'=>'cell-form',
)); ?>

<div class="form" style="margin-bottom:15px;">
<p class ="note">
*Create generic cells that bypasses all validation except the uniqueness of the cell numbers. <br/>
</p>
<?php echo $form->errorSummary($cellModel); ?>

<table class="double-form"><tr>
	<td>
		<div class="row">
			<?php echo $form->labelEx($cellModel,'ref_num_id'); ?>
			<?php echo $form->dropDownList($cellModel, 'ref_num_id', 
							CHtml::listData(RefNum::model()->inOrder()->findAll(array('condition'=>'id <> 70')), 'id','number'), 
							array(
								'prompt'=>' -Select Reference No.- ',
								'onchange'=>'refSelected(this)',
								'style'=>'width:152px'
							)); ?>
			<?php echo CHtml::link('New Ref No.','#',array('class'=>'refnum-link', 'onClick'=>'$("#refnum-dialog").dialog("open");')); ?><div class="clear"></div>
			<?php echo $form->error($cellModel,'ref_num_id'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($kitModel,'celltype_id'); ?>
			<?php echo $form->dropDownList($kitModel, 'celltype_id', 
							CHtml::listData(Celltype::model()->inOrder()->findAll(), 'id','name'),
							array(
								'prompt'=>'-Select Type-',
								'class'=>'celltype-dropdown',
								'onChange'=>'typeSelected(this)',
								'style'=>'width:152px',
							)); ?>
			<?php echo CHtml::link('New Cell Type','#',array('class'=>'celltype-link','onClick'=>'$("#celltype-dialog").dialog("open");')); ?><div class="clear"></div>
			<?php echo $form->error($kitModel,'celltype_id'); ?>
		</div>
		<div class="row multidrop5">
			<?php echo CHtml::label('Anode Lots', 'Kit_anodeIds'); ?>
			<?php echo CHtml::DropDownList('Kit[anodeIds][]', $kitModel->anodeIds, 
							CHtml::listData(Electrode::model()->anodes()->notGeneric()->findAll(), 'id','lot_num'), 
							array(
								'multiple'=>'multiple',
								'prompt'=>' -Select Anode Lots- ',
								'style'=>'width:152px',
								'size'=>5,
							)); ?>
			<?php echo $form->error($kitModel,'anodeIds'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($cellModel,'fill_date'); ?>
			<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$cellModel,
			        'attribute'=>'fill_date',
			    	'value'=>$cellModel->fill_date,
			        // additional javascript options for the date picker plugin
			        'options'=>array(
			            'showAnim'=>'slideDown',
			            'changeMonth'=>true,
			            'changeYear'=>true,
			            'dateFormat' => 'yy-mm-dd',
			        ),
			        'htmlOptions'=>array(
						'style'=>'width:150px;',
					),
			    ));
			?>
			<?php echo $form->error($cellModel,'fill_date'); ?>
		</div>
	</td>
	<td>
		<div class="row">
			<?php echo $form->labelEx($cellModel,'eap_num'); ?>
			<?php echo $form->textField($cellModel,'eap_num', array(
							'style'=>'width:150px;',
						)); ?> <span style="padding-left:10px"; ><em><b>ex.</b></em> EAP 00999 ADD 1A</span>
			<?php echo $form->error($cellModel,'eap_num'); ?>
		</div>
		<div class="row">
			<div style="padding-top:10px; width:300px"><span id="last-serial"></span></div>
		</div>
		<div class="row multidrop5">
			<?php echo CHtml::label('Cathode Lots', 'Kit_cathodeIds'); ?>
			<?php echo CHtml::DropDownList('Kit[cathodeIds][]', $kitModel->cathodeIds, 
							CHtml::listData(Electrode::model()->cathodes()->notGeneric()->findAll(), 'id','lot_num'), 
							array(
								'multiple'=>'multiple',
								'prompt'=>' -Select Cathode Lots- ',
								'style'=>'width:152px',
								'size'=>5,
							)); ?>
			<?php echo $form->error($kitModel,'cathodeIds'); ?>
		</div>
	</td>
</tr></table>
	<div class="row">
		<?php echo CHtml::label('Enter Serial String', 'Kit_serial_string')?>
		<?php echo CHtml::textField('Kit[serial_string]', '', array(
						'style'=>'width:98%;',
					)); ?> 
	</div>
</div>
<?php echo CHtml::ajaxSubmitButton('Submit',array('cell/ajaxcreategenericcells'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'celltype-dialog',
	'options'=>array(
		'title'=>'Add New Cell Type', 
		'autoOpen' => false,
		'width'=>500,
		'modal'=>true,
		'buttons'=>array(
			'Submit'=>'js:function(){addNewCellType();}',
			'Cancel'=>'js:function(){$( this ).dialog( "close" );}',
		),
	),
));

	$this->renderPartial('//celltype/_form',array('celltypeModel'=>new Celltype));

$this->endWidget('zii.widgets.jui.CJuiDialog'); 
?>

<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'refnum-dialog',
	'options'=>array(
		'title'=>'Add New Reference Number', 
		'autoOpen' => false,
		'width'=>500,
		'modal'=>true,
		'buttons'=>array(
			'Submit'=>'js:function(){addNewRefNum();}',
			'Cancel'=>'js:function(){$( this ).dialog( "close" );}',
		),
	),
));

	$this->renderPartial('//refnum/_form',array('refnumModel'=>new RefNum));

$this->endWidget('zii.widgets.jui.CJuiDialog'); 
?>

<script type="text/javascript">
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
    	   var alertString = cells.success.length+' cells were created succesfully. Serial numbers: \n';
    	   cells.success.forEach(function(cell) {
    		   alertString += cell.serial + '\n';
    	   });
    	   alert(alertString);

    	   if(cells.failure.length > 0){
	    	   alertString = 'ERROR!!! ' + cells.failure.length+' cells failed creation. Serial numbers: \n';
	    	   cells.failure.forEach(function(cell) {
	    		   alertString += cell.serial + "-";
	    		   cell.errors.forEach(function(error){
	        		   alertString += error + '\n'
	    		   });
	    	   });
	    	   alert(alertString);
    	   }
    	   
    	   location.reload();
    	}
    	catch(e)
    	{
    		$('#cellcreate-form').prepend(data);
    		console.log(e.message);
    	}
    }
	$('#submit-button').removeAttr('disabled');
}

$(document).ready(function($) {
	

	$(document).on('click', '#auto-fill', function(event) {
		if($('#serials_1').val() == ''){
			alert('Enter the first serial number in line 1 before clicking this button');
			$('.serial-nums').val('');
		}
		else{
			var serial_one = +$('#serials_1').val();
			$('.serial-nums').not('#serials_1').each(function(key, value){
				var new_serial = '' + (serial_one + key+1);
				while(new_serial.length < 4){
					new_serial = '0' + new_serial;
				}
				$(this).val(new_serial);
			});
		}
		return false;
	});

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
	
	$('#submit-button').on('click', function(event) {
		$('.errorSummary').remove();

		if($('#Cell_fill_date').val() == ''){
			alert('You need to select a filling date prior to submitting.');	
			return false;
		}
		if($('#Kit_serial_string').val() == ''){
			alert('You need to copy and paste the serial number string from the cell fill log before submitting.');	
			return false;
		}
	});

	$('body').on('focus', '.hasDatePicker', function(event) {
		$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
	});
});

function typeSelected(sel)
{
	var celltype = $('option:selected', $(sel)).text();
	var celltype_id = $('option:selected', $(sel)).val();

	$('.serial-cell span').text(celltype+'-');

	$.ajax({
		type:'get',
		url: '<?php echo $this->createUrl('kit/lastserial'); ?>',
		data:
		{
			celltype_id: celltype_id.toString(),
		},
		success: function(data){
			$('#last-serial').text(
					"Highest serial number used for this cell type was: " +
					celltype + "-" + data
			 );
		},
	});
}

function refSelected(sel)
{
	var ref = $('option:selected', $(sel)).text();
	$("#Cell_eap_num").attr("value","EAP "+ ref + " ADD N/A");
	//$("#Cell_eap_num").attr('disabled', 'disabled');
}

function addNewCellType(){

	$('.errorSummary').remove();
	// populate the spare cell serial dropdown
	
	$.ajax({
		type:'post',
		url: '<?php  echo $this->createUrl('celltype/ajaxaddcelltype'); ?>',
		data:$('#celltype-form').serialize(),
		success: function(data){
			$('#celltype-dialog').dialog('close');
			alert(data);
			location.reload();
		},
	});
}

function addNewRefNum(){

	$('.errorSummary').remove();
	// populate the spare cell serial dropdown
	
	$.ajax({
		type:'post',
		url: '<?php  echo $this->createUrl('refnum/ajaxaddreferencenumber'); ?>',
		data:$('#ref-num-form').serialize(),
		success: function(data){
			$('#refnum-dialog').dialog('close');
			alert(data);
			location.reload();
		},
	});
}

</script>
