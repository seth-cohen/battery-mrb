<?php
/* @var $this ChannelController */
/* @var $model Channel */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'channel-form',
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
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number', array('disabled'=>'disabled')); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'cycler_id'); ?>
		<?php echo CHtml::textField('Cycler_name', $model->cycler->name,array('size'=>20,'maxlength'=>20, 'disabled'=>'disabled')); ?>
		<?php echo $form->error($model,'cycler_id'); ?>
	</div>
</div>
<div class="clear"></div>
	
<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'max_charge_rate'); ?>
		<?php echo $form->textField($model,'max_charge_rate'); ?>
		<?php echo $form->error($model,'max_charge_rate'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'max_discharge_rate'); ?>
		<?php echo $form->textField($model,'max_discharge_rate'); ?>
		<?php echo $form->error($model,'max_discharge_rate'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'min_voltage'); ?>
		<?php echo $form->textField($model,'min_voltage'); ?>
		<?php echo $form->error($model,'min_voltage'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'max_voltage'); ?>
		<?php echo $form->textField($model,'max_voltage'); ?>
		<?php echo $form->error($model,'max_voltage'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'in_commission'); ?>
		<?php echo $form->dropDownList($model, 'in_commission', array("0"=>"No", "1"=>"Yes"), array()); ?>
		<?php echo $form->error($model,'in_commission'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'in_use'); ?>
		<?php echo $form->dropDownList($model, 'in_use', array("0"=>"No", "1"=>"Yes"), array()); ?>
		<?php echo $form->error($model,'in_use'); ?>
	</div>
</div>
<div class="clear"></div>

	<div class="row">
		<?php echo $form->labelEx($model,'multirange'); ?>
		<?php echo $form->dropDownList($model, 'multirange', array("0"=>"No", "1"=>"Yes"), array()); ?>
		<?php echo $form->error($model,'multirange'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<?php if($model->activeTestAssignment != null):?> 
			<p><?php echo CHtml::link($model->activeTestAssignment->cell->kit->getFormattedSerial(), array('/cell/view','id'=>$model->activeTestAssignment->cell->id))?> is actively on test on this channel</p>
	<?php endif; ?>
<?php $this->endWidget(); ?>

</div><!-- form -->