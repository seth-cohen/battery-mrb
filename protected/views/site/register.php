<?php
/* @var $this SiteController */
/* @var $model RegistrationForm */
/* @var $form CActiveForm  */
/* @var $department Department */

$this->pageTitle=Yii::app()->name . ' - Register';
$this->breadcrumbs=array(
	'Register',
);
?>

<h1>Register New User</h1>

<p>Please fill out the following form to add new user to system:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registration-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<table class="double-form"><tr>
	<td>
		<div class="row">
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username'); ?>
			<?php echo $form->error($model,'username'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'first_name'); ?>
			<?php echo $form->textField($model,'first_name'); ?>
			<?php echo $form->error($model,'first_name'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'last_name'); ?>
			<?php echo $form->textField($model,'last_name'); ?>
			<?php echo $form->error($model,'last_name'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email'); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
	</td>
	<td>
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
		<div class="row">
			<?php echo $form->labelEx($model,'depart_id'); ?>
			<?php echo CHtml::activeDropDownList($model, 'depart_id', 
							CHtml::listData(Department::model()->findAll(), 'id','name'), array('prompt'=>'Select Department...')); ?>
			<?php echo $form->error($model,'depart_id');?>
		</div>
	</td>	
	</tr></table>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Register'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
