<?php
/* @var $this ChamberController */
/* @var $model Chamber */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'brand'); ?>
		<?php echo $form->textField($model,'brand',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'model'); ?>
		<?php echo $form->textField($model,'model',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'serial_num'); ?>
		<?php echo $form->textField($model,'serial_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'in_commission'); ?>
		<?php echo $form->dropDownList($model, 'in_commission', array('0'=>'No', '1'=>'Yes'), array('prompt'=>'-Select-')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'govt_tag_num'); ?>
		<?php echo $form->textField($model,'govt_tag_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cycler_id'); ?>
		<?php echo $form->dropDownList($model, 'cycler_id', Cycler::forList(), array('prompt'=>'-Select-')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'min_temp'); ?>
		<?php echo $form->textField($model,'min_temp'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_temp'); ?>
		<?php echo $form->textField($model,'max_temp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->