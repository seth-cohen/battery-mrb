<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cycler-form',
	'enableClientValidation'=>true,
	'enableAjaxValidation'=>true,
)); ?>

<div class="form">
<p class="note">Fields with <span class="required">*</span> are required.</p>
<?php echo $form->errorSummary($model); ?>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'sy_number'); ?>
		<?php echo $form->textField($model,'sy_number'); ?>
		<?php echo $form->error($model,'sy_number'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>25,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'maccor_job_num'); ?>
		<?php echo $form->textField($model,'maccor_job_num',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'maccor_job_num'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'govt_tag_num'); ?>
		<?php echo $form->textField($model,'govt_tag_num',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'govt_tag_num'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'cal_date'); ?>
		<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$model,
			        'attribute'=>'cal_date',
			    	'value'=>$model->cal_date,
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
		<?php echo $form->error($model,'cal_date'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'cal_due_date'); ?>
		<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$model,
			        'attribute'=>'cal_due_date',
			    	'value'=>$model->cal_due_date,
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
		<?php echo $form->error($model,'cal_due_date'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
			<?php echo $form->labelEx($model,'calibrator_id'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array( 
				'model'=>$model,
			 	'attribute'=>'calibrator_search',
				'sourceUrl'=>$this->createUrl('/user/ajaxUserSearch'),
				'options' => array(
					'select'=>'js: 
						function(event, ui){
							$("#Cycler_calibrator_id").attr("value", ui.item.id);
						}',
				),
				'htmlOptions'=>array(
					'style'=>'width:152px;',
				),
			)); ?>
			<?php echo $form->error($model,'calibrator_search'); ?>
			<?php echo $form->hiddenField($model, 'calibrator_id'); ?>
		</div>
</div>
<div class="clear"></div>

<?php if($this->action->id == 'update'):?>
<div class="row buttons">
	<?php echo CHtml::ajaxSubmitButton('Submit Gerneral Only',array('cycler/ajaxupdate', 'id'=>$model->id, 'full'=>0), array('success'=>'checkSuccess'), array("id"=>"submit-button-partial")); ?>
</div>
<?php endif; ?>

</div><!-- form -->

<div class="shadow border" id="channels-wrapper" style="margin:auto; margin-top:15px"> 
<h2 style="text-align:center; width: 100%;">Channel Details</h2>
<p style="margin:auto;">*Use a new line for each set of channels with different properties.</p>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>"cellspares-grid",
	'template'=>'{items}',
	'dataProvider'=>$channelsDataProvider,
	'columns'=>array(
		array(
			'name'=>'Item No.',
			'value'=>'$data["id"]',
		),
		array(
			'header'=>'Num Channels',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][num]', $data["numChannels"], array(
						'size'=>'3',
						'class'=>'input-'.$data['id'],
				));
			},
		),
		array(
			'header'=>'Min Voltage',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][minV]', $data["minV"], array(
						'style'=>'width:50px;',
						'class'=>'input-'.$data['id'],
				));
			},
		),
		array(
			'header'=>'Max Voltage',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][maxV]', $data["maxV"], array(
						'style'=>'width:50px;',
						'class'=>'input-'.$data['id'],
				));
			},
		),
		array(
			'header'=>'Max Charge Rate (A)',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][maxC]', $data["maxC"], array(
						'style'=>'width:50px;',
						'class'=>'input-'.$data['id'],
				));
			},
		),
		array(
			'header'=>'Max Discharge Rate (A)',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][maxD]', $data["maxD"], array(
						'style'=>'width:50px;',
						'class'=>'input-'.$data['id'],
				));
			},
		),
		array(
			'header'=>'Multirange',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::dropDownList('Channels['.$data['id'].'][multi]', $data["multi"], 
					array('0'=>'No', '1'=>'Yes'), 
					array(
						'prompt' => '-Select-',
						'class'=>'input-'.$data['id'],
					)
				);
			},
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>
</div>

<?php echo CHtml::ajaxSubmitButton('Filter',array('cycler/create'), array(), array("style"=>'display:none')); ?>

<?php if($this->action->id == 'update'):?>
	<?php echo CHtml::ajaxSubmitButton('Submit All',array('cycler/ajaxupdate', 'id'=>$model->id, 'full'=>1), array('success'=>'checkSuccess'), array("id"=>"submit-button")); ?>
<?php else:?>
	<?php echo CHtml::ajaxSubmitButton('Submit All',array('cycler/ajaxcreate'), array('success'=>'checkSuccess'), array("id"=>"submit-button")); ?>
<?php endif; ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">
function checkSuccess(data) {	
	if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
	else if (data=='cycler save complete')
	{
		alert('Successfully saved cycler details.');
		 location = '<?php echo $this->createUrl('index');?>';
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
    	   location = '<?php echo $this->createUrl('index');?>';
    	}
    	catch(e)
    	{
    		$('#cycler-form').prepend(data);
    		console.log(e.message);
    	}
    }
}

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
	
	jQuery('#submit-button').on('click', function(event) {
		var noChannels = true;
		$('.errorSummary').remove();
		
		$('input[name="Channels[1][num]').each(function () {
	        if (this.value != '') {
		        // channel group exists 
	        	noChannels = false; 
	        	var elClass = $(this).attr("class");
	            $('.'+elClass).not(this).each(function(index, element){
					if(element.value == ''){
						// then the data was incomplete
	    	            noChannels = 'incomplete'; 
					}
	            });
	        }
		});

		if(noChannels == true)
		{
			alert('You must add at least one type of channels to the cycler');
			return false;
		}
		else if(noChannels == 'incomplete'){
			alert('Incomplete data for at least one channel group');
			return false;
		}
	});

});
</script>