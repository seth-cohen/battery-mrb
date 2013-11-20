<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'id'=>'cell-search-form',
)); ?>


	<div class="row">
		<?php echo $form->label($model,'serial_search'); ?>
		<?php echo $form->textField($model,'serial_search',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kit_id'); ?>
		<?php echo $form->textField($model,'kit_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'refnum_search'); ?>
		<?php echo $form->textField($model,'refnum_search',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'eap_num'); ?>
		<?php echo $form->textField($model,'eap_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'celltype_search'); ?>
		<?php echo $form->textField($model,'celltype_search',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'stacker_search'); ?>
		<?php echo $form->textField($model,'stacker_search',array('size'=>10,'maxlength'=>10)); ?>	</div>

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
		<?php echo $form->label($model,'filler_search'); ?>
		<?php echo $form->textField($model,'filler_search',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fill_date'); ?>
		<?php echo $form->textField($model,'fill_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'inspector_search'); ?>
		<?php echo $form->textField($model,'inspector_search',array('size'=>10,'maxlength'=>10)); ?>
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