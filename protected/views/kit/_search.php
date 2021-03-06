<?php
/* @var $this KitController */
/* @var $model Kit */
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
		<?php echo $form->textField($model,'serial_num',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ref_num_id'); ?>
		<?php echo $form->textField($model,'ref_num_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kitter_id'); ?>
		<?php echo $form->textField($model,'kitter_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kitting_date'); ?>
		<?php echo $form->textField($model,'kitting_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'celltype_id'); ?>
		<?php echo $form->textField($model,'celltype_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->