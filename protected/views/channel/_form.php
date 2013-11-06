<?php
/* @var $this ChannelController */
/* @var $model Channel */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'channel-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number'); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cycler_id'); ?>
		<?php echo $form->textField($model,'cycler_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'cycler_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_charge_rate'); ?>
		<?php echo $form->textField($model,'max_charge_rate'); ?>
		<?php echo $form->error($model,'max_charge_rate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_discharge_rate'); ?>
		<?php echo $form->textField($model,'max_discharge_rate'); ?>
		<?php echo $form->error($model,'max_discharge_rate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'multirange'); ?>
		<?php echo $form->textField($model,'multirange'); ?>
		<?php echo $form->error($model,'multirange'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'in_use'); ?>
		<?php echo $form->textField($model,'in_use'); ?>
		<?php echo $form->error($model,'in_use'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'in_commission'); ?>
		<?php echo $form->textField($model,'in_commission'); ?>
		<?php echo $form->error($model,'in_commission'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'min_voltage'); ?>
		<?php echo $form->textField($model,'min_voltage'); ?>
		<?php echo $form->error($model,'min_voltage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_voltage'); ?>
		<?php echo $form->textField($model,'max_voltage'); ?>
		<?php echo $form->error($model,'max_voltage'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->