<?php
/* @var $this SiteController */
/* @var $model RegistrationForm */
/* @var $form CActiveForm  */
/* @var $department Department */

$this->pageTitle=Yii::app()->name . ' - Register';
$this->breadcrumbs=array(
	'Change Password',
);
?>

<h1>User Password Change Form</h1>

<p>Please fill out the following form to change your password:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'password-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('disabled'=>'disabled')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>	
	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyPassword'); ?>
		<?php echo $form->passwordField($model,'verifyPassword'); ?>
		<?php echo $form->error($model,'verifyPassword'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Change Password and Login'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
