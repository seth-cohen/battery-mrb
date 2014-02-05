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

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>
</div><!-- form -->

<div class="shadow border" style="margin-top:15px;">
<h2 style="text-align:center">Cells on NCR-<?php echo $model->number; ?></h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'channel-grid',
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
		'disposition_string',
	),		
	'emptyText'=>'Oops, no cells on this NCR',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>	
</div>

<?php echo CHtml::ajaxSubmitButton('Submit',array('ncr/ajaxupdate'), array('success'=>'checkSuccess'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">
jQuery(function($){
	$('#channel-grid .filters').attr('align','center');
	/*$('#channelassignment-grid .filters').children(':nth-child(1)').text('Change');*/
	$('#channel-grid .filters').children(':nth-child(1)').text('Remove Cell');
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
    	   var channels = $.parseJSON(data);
    	   var alertString = channels.length+' channels were added to the Cycler. Channel Details: \n';
    	   channels.forEach(function(channel) {
    		   alertString += channel.num + ': minV:' + channel.minV + ' maxV:' + channel.maxV + ' maxC:' + channel.maxC 
    		   					+ ' maxD:' + channel.maxD + ' multi:' + channel.multi + '\n';
    	   });
    	   alert(alertString);
    	   location.reload();
    	}
    	catch(e)
    	{
    		$('#cycler-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>