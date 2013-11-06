<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cell-form',
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
		<?php echo $form->textField($model,'serial_num',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'serial_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kit_id'); ?>
		<?php echo $form->textField($model,'kit_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kit_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ref_num'); ?>
		<?php echo $form->textField($model,'ref_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'ref_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'eap_num'); ?>
		<?php echo $form->textField($model,'eap_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'eap_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'celltype_id'); ?>
		<?php echo $form->textField($model,'celltype_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'celltype_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stacker_id'); ?>
		<?php echo $form->textField($model,'stacker_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'stacker_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'stack_date'); ?>
		<?php echo $form->textField($model,'stack_date'); ?>
		<?php echo $form->error($model,'stack_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dry_wt'); ?>
		<?php echo $form->textField($model,'dry_wt'); ?>
		<?php echo $form->error($model,'dry_wt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wet_wt'); ?>
		<?php echo $form->textField($model,'wet_wt'); ?>
		<?php echo $form->error($model,'wet_wt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'filler_id'); ?>
		<?php echo $form->textField($model,'filler_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'filler_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fill_date'); ?>
		<?php echo $form->textField($model,'fill_date'); ?>
		<?php echo $form->error($model,'fill_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'inspector_id'); ?>
		<?php echo $form->textField($model,'inspector_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'inspector_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'inspection_date'); ?>
		<?php echo $form->textField($model,'inspection_date'); ?>
		<?php echo $form->error($model,'inspection_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->