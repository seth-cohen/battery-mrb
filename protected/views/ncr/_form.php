<?php
/* @var $this NcrController */
/* @var $model Ncr */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ncr-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
)); ?>

<div class="form">
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>10,'maxlength'=>10, 'disabled'=>'disabled')); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$model,
			        'attribute'=>'date',
			    	'value'=>$model->date,
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
		<?php echo $form->error($model,'date'); ?>
	</div>
</div>
<div class="clear"></div>	

<div class="left-form">
	<div class="row">
		<?php echo CHtml::label('Number of Cells on the NCR', 'Count_cells'); ?>
		<?php echo CHtml::textField('Count_cells', count($model->cells), array('size'=>10,'maxlength'=>10, 'disabled'=>'disabled')); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo CHtml::label('Open Cells on the NCR', 'Count_opencells'); ?>
		<?php echo CHtml::textField('Count_opencells', count($model->openCells), array('size'=>10,'maxlength'=>10, 'disabled'=>'disabled')); ?>
	</div>
</div>
<div class="clear"></div>	

</div><!-- form -->

<div class="shadow border" style="margin-top:15px;">
<h2 style="text-align:center">Cells on NCR-<?php echo $model->number; ?></h2>
<span style="float:left">*You can remove cells from the NCR by checking the appropriate box and clicking 'Submit'</span>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cell-grid',
	'dataProvider'=>$ncrCellDataProvider,
	'filter'=>$ncrCell,
	'columns'=>array(
		array(
            'id'=>'removeId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
		array(
			'name'=>'ncr_search',
			'value'=>'"NCR-".$data->ncr->number',
		),
		 array(
			'name'=>'serial_search',
		 	'type'=>'raw',
			'value'=>function($data, $row){
				return 
				CHtml::link($data->cell->kit->getFormattedSerial(), 
					array("cell/view", "id"=>$data->cell->id)
				);
			}
		),
		array(
			'name'=>'refnum_search',
			'value'=>'$data->cell->refNum->number',
		),
		array(
			'name'=>'disposition_string',
			'type'=>'raw',
			'value'=>function($data, $row){
				return 
				CHtml::activeDropDownList($data,"disposition",
					array(
						"0"=>"Open",
						"1"=>"Scrap",
						"2"=>"Eng Use Only",
						"3"=>"Accept",
						"4"=>"Use As Is",
					), 
					array(
						"prompt"=>"-N/A-",
						"onChange"=>"dispoSelected(this)",
						"style"=>"width:100px",
						'data-cell-id'=>$data->cell->id,
						'data-ncr-id'=>$data->ncr->id,
					)
				);
			},
		),
	),		
	'emptyText'=>'Oops, no cells on this NCR',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>	
</div>

<?php echo CHtml::ajaxSubmitButton('Remove Cells',array('ncr/ajaxupdate', 'id'=>$model->id), array('success'=>'checkSuccess'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">
jQuery(function($){
	$('#cell-grid .filters').attr('align','center');
	/*$('#channelassignment-grid .filters').children(':nth-child(1)').text('Change');*/
	$('#cell-grid .filters').children(':nth-child(1)').text('Remove Cell');
});

function checkSuccess(data) {	
	if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
    	try
    	{
    	   var cells = $.parseJSON(data);
    	   var alertString = cells.length+' cells were attempted to be removed from the NCR. Cell Details: \n';
    	   cells.forEach(function(cell) {
    		   alertString += cell.serial + ' had disposition of ' + cell.dispo + ' and was '; 
    		   alertString += cell.success?'':'NOT'; 
    		   alertString +=' removed\n';
    	   });
    	   alert(alertString);
    	   location.reload();
    	}
    	catch(e)
    	{
    		$('#ncr-form').prepend(data);
    		console.log(e.message);
    	}
    }
}

function dispoSelected(sel)
{
	var ncrElement = sel.id.toString().replace("Dispo","Ncr");
	
	var ncr_id = $(sel).data("ncr-id");
	var cell_id =$(sel).data("cell-id");
	var dispo = $('option:selected', $(sel)).attr("value");
	
	$.ajax({
		url: '<?php echo $this->createUrl('/ncr/ajaxsetdispo'); ?>',
		type: 'POST',
		data: 
		{
			id: ncr_id,
			cell_id: cell_id,
			dispo: dispo,
		},
		success: function(data) {
			var message;
			if(data == '1'){
				message = $("<br/><span style='color:green'>Change Successful</span>");
				$(sel).css('border', '2px solid green');
				$(sel).parent().append(message);
				setTimeout(function() {
					$(sel).css('border', '1px solid');
					message.remove();
				}, 2000);
			}else{
				message = $("<br/><span style='color:red'>Change Failed</span>");
				$(sel).css('border', '2px solid red');
				$(sel).parent().append(message);
				setTimeout(function() {
					$(sel).css('border', '1px solid');
					message.remove();
				}, 2000);
			}
		},
	});	
}
</script>