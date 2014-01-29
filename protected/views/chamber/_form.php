<?php
/* @var $this ChamberController */
/* @var $model Chamber */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'chamber-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'name'); ?>
			<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'govt_tag_num'); ?>
			<?php echo $form->textField($model,'govt_tag_num',array('size'=>20,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'govt_tag_num'); ?>
		</div>
	</div>
	<div class="clear"></div>
		
	<div class="left-form">	
		<div class="row">
			<?php echo $form->labelEx($model,'brand'); ?>
			<?php echo $form->textField($model,'brand',array('size'=>20,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'brand'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'min_temp'); ?>
			<?php echo $form->textField($model,'min_temp'); ?>
			<?php echo $form->error($model,'min_temp'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">	
		<div class="row">
			<?php echo $form->labelEx($model,'model'); ?>
			<?php echo $form->textField($model,'model',array('size'=>20,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'model'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'max_temp'); ?>
			<?php echo $form->textField($model,'max_temp'); ?>
			<?php echo $form->error($model,'max_temp'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">	
		<div class="row">
			<?php echo $form->labelEx($model,'serial_num'); ?>
			<?php echo $form->textField($model,'serial_num',array('size'=>20,'maxlength'=>50)); ?>
			<?php echo $form->error($model,'serial_num'); ?>
		</div>
	</div>
	
<?php if($this->action->id == 'update'):?>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'in_commission'); ?>
			<?php echo $form->dropDownList($model, 'in_commission', array("0"=>"No", "1"=>"Yes"), array()); ?>
			<?php echo $form->error($model,'in_commission'); ?>
		</div>
	</div>
<?php  endif; ?>
<div class="clear"></div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->