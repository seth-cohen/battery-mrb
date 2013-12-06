<?php
/* @var $this BatteryController */
/* @var $model Battery */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'battery-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'batterytype_id'); ?>
		<?php echo $form->textField($model,'batterytype_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'batterytype_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ref_num_id'); ?>
		<?php echo $form->textField($model,'ref_num_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'ref_num_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'eap_num'); ?>
		<?php echo $form->textField($model,'eap_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'eap_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'serial_num'); ?>
		<?php echo $form->textField($model,'serial_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'serial_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'assembler_id'); ?>
		<?php echo $form->textField($model,'assembler_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'assembler_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'assembly_date'); ?>
		<?php echo $form->textField($model,'assembly_date'); ?>
		<?php echo $form->error($model,'assembly_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ship_date'); ?>
		<?php echo $form->textField($model,'ship_date'); ?>
		<?php echo $form->error($model,'ship_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'location'); ?>
		<?php echo $form->textField($model,'location',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'location'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->