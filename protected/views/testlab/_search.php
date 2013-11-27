<?php
/* @var $this TestlabController */
/* @var $model TestAssignment */
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
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->