<?php
/* @var $this ManufacturingController */
/* @var $model Cathode */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'createcathode-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'lot_num'); ?>
		<?php echo $form->textField($model,'lot_num'); ?>
		<?php echo $form->error($model,'lot_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'eap_num'); ?>
		<?php echo $form->textField($model,'eap_num'); ?>
		<?php echo $form->error($model,'eap_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'coater_id'); ?>
		<?php echo $form->textField($model,'coater_id'); ?>
		<?php echo $form->error($model,'coater_id'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->