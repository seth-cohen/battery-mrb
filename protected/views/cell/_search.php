<?php
/* @var $this CellController */
/* @var $model Cell */
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
		<?php echo $form->label($model,'serial_num'); ?>
		<?php echo $form->textField($model,'serial_num',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kit_id'); ?>
		<?php echo $form->textField($model,'kit_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ref_num'); ?>
		<?php echo $form->textField($model,'ref_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'eap_num'); ?>
		<?php echo $form->textField($model,'eap_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'celltype_id'); ?>
		<?php echo $form->textField($model,'celltype_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stacker_id'); ?>
		<?php echo $form->textField($model,'stacker_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stack_date'); ?>
		<?php echo $form->textField($model,'stack_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dry_wt'); ?>
		<?php echo $form->textField($model,'dry_wt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wet_wt'); ?>
		<?php echo $form->textField($model,'wet_wt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'filler_id'); ?>
		<?php echo $form->textField($model,'filler_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fill_date'); ?>
		<?php echo $form->textField($model,'fill_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'inspector_id'); ?>
		<?php echo $form->textField($model,'inspector_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'inspection_date'); ?>
		<?php echo $form->textField($model,'inspection_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->