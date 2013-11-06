<?php
/* @var $this ChannelController */
/* @var $model Channel */
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
		<?php echo $form->label($model,'number'); ?>
		<?php echo $form->textField($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cycler_id'); ?>
		<?php echo $form->textField($model,'cycler_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_charge_rate'); ?>
		<?php echo $form->textField($model,'max_charge_rate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_discharge_rate'); ?>
		<?php echo $form->textField($model,'max_discharge_rate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'multirange'); ?>
		<?php echo $form->textField($model,'multirange'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'in_use'); ?>
		<?php echo $form->textField($model,'in_use'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'in_commission'); ?>
		<?php echo $form->textField($model,'in_commission'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'min_voltage'); ?>
		<?php echo $form->textField($model,'min_voltage'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_voltage'); ?>
		<?php echo $form->textField($model,'max_voltage'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->