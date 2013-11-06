<?php
/* @var $this CyclerController */
/* @var $model Cycler */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cycler-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'sy_number'); ?>
		<?php echo $form->textField($model,'sy_number'); ?>
		<?php echo $form->error($model,'sy_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'num_channels'); ?>
		<?php echo $form->textField($model,'num_channels'); ?>
		<?php echo $form->error($model,'num_channels'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cal_date'); ?>
		<?php echo $form->textField($model,'cal_date'); ?>
		<?php echo $form->error($model,'cal_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cal_due_date'); ?>
		<?php echo $form->textField($model,'cal_due_date'); ?>
		<?php echo $form->error($model,'cal_due_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'calibrator_id'); ?>
		<?php echo $form->textField($model,'calibrator_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'calibrator_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maccor_job_num'); ?>
		<?php echo $form->textField($model,'maccor_job_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'maccor_job_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'govt_tag_num'); ?>
		<?php echo $form->textField($model,'govt_tag_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'govt_tag_num'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->