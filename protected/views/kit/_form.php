<?php
/* @var $this KitController */
/* @var $model Kit */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kit-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'serial_num'); ?>
		<?php echo $form->textField($model,'serial_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'serial_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ref_num_id'); ?>
		<?php echo $form->textField($model,'ref_num_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'ref_num_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'anode_id'); ?>
		<?php echo $form->textField($model,'anode_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'anode_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cathode_id'); ?>
		<?php echo $form->textField($model,'cathode_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'cathode_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kitter_id'); ?>
		<?php echo $form->textField($model,'kitter_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kitter_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kitting_date'); ?>
		<?php echo $form->textField($model,'kitting_date'); ?>
		<?php echo $form->error($model,'kitting_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'celltype_id'); ?>
		<?php echo $form->textField($model,'celltype_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'celltype_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->