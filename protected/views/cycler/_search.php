<?php
/* @var $this CyclerController */
/* @var $model Cycler */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sy_number'); ?>
		<?php echo $form->textField($model,'sy_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'num_channels'); ?>
		<?php echo $form->textField($model,'num_channels'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cal_date'); ?>
		<?php echo $form->textField($model,'cal_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cal_due_date'); ?>
		<?php echo $form->textField($model,'cal_due_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'calibrator_id'); ?>
		<?php echo $form->textField($model,'calibrator_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'maccor_job_num'); ?>
		<?php echo $form->textField($model,'maccor_job_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'govt_tag_num'); ?>
		<?php echo $form->textField($model,'govt_tag_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->