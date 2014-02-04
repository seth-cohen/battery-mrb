<?php
/* @var $this CyclerController */
/* @var $model Cycler */
/* @var $form CActiveForm */
/* @var $channelsDataProvider CArrayDataProvider */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cycler-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'enableAjaxValidation'=>true,
)); ?>

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

</div><!-- form -->

<div class="shadow border" id="channels-wrapper" style="margin:auto; margin-top:15px"> 
<h2 style="text-align:center; width: 100%;">Channel Details</h2>
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
				return	CHtml::textField('Cycler[Channels]['.$data['id'].'][num]', '', array(
						'size'=>'3',
				));
			},
		),
		array(
			'header'=>'Multirange',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::dropDownList('Cycler[Channels]['.$data['id'].'][multi]', array(), 
					array('0'=>'No', '1'=>'Yes'), 
					array(
						'prompt' => '-Select-'
					)
				);
			},
		),
		array(
			'header'=>'Min Voltage',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][minV]', '', array(
						'style'=>'width:50px;',
				));
			},
		),
		array(
			'header'=>'Max Voltage',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][maxV]', '', array(
						'style'=>'width:50px;',
				));
			},
		),
		array(
			'header'=>'Max Charge Rate (A)',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][maxC]', '', array(
						'style'=>'width:50px;',
				));
			},
		),
		array(
			'header'=>'Max Discharge Rate (A)',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Channels['.$data['id'].'][maxD]', '', array(
						'style'=>'width:50px;',
				));
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

<?php echo CHtml::ajaxSubmitButton('Submit',array('cycler/ajaxcreate'), array('success'=>'checkSuccess'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">

jQuery(function($) {
	jQuery('#submit-button').on('click', function(event) {
		var noChannels = true;
		$('.errorSummary').remove();
		
		$('input[name="Cycler[Channels][1][num]').each(function () {
	        if (this.value() != '') {
	            noChannels = false; 
	        }
		});

		if(noChannels)
		{
			alert('You must select at least one cell to stack');
			return false;
		}
	});

});
</script>
