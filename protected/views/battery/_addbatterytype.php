<?php
/* @var $this BatteryController */
/* @var $batterytypeModel Batterytype */
/* @var $form CActiveForm */
?>

<div class="form border" style="padding:15px; background-color:white;">
<?php echo CHtml::link('Hide','#',array('id'=>'batterytype-hide-link', 'style'=>'float:right; padding-right:20px;')) ?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'batterytype-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>
	<h2 style="text-align:center">Register New Battery Type</h2> 
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($batterytypeModel); ?>
	
<div class="left-form">
	<div class="row">
        <?php echo $form->labelEx($batterytypeModel,'part_num'); ?>
        <?php echo $form->textField($batterytypeModel,'part_num',array('size'=>20,'maxlength'=>50)); ?>
        <?php echo $form->error($batterytypeModel,'part_num'); ?>
    </div>
</div>
<div class="right-form">
	<div class="row">
        <?php echo $form->labelEx($batterytypeModel,'name'); ?>
        <?php echo $form->textField($batterytypeModel,'name',array('size'=>50,'maxlength'=>50)); ?>
        <?php echo $form->error($batterytypeModel,'name'); ?>
    </div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($batterytypeModel,'celltype_id'); ?>
		<?php echo $form->dropDownList($batterytypeModel, 'celltype_id', 
							CHtml::listData(Celltype::model()->findAll(), 'id','name'),
							array(
								'prompt'=>'-Select Type-',
								'class'=>'celltype-dropdown',
								'onChange'=>'typeSelected(this)',
								'style'=>'width:152px',
							)); ?>
		<?php echo $form->error($batterytypeModel,'celltype_id'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($batterytypeModel,'num_cells'); ?>
		<?php echo $form->textField($batterytypeModel,'num_cells',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($batterytypeModel,'num_cells'); ?>
	</div>
</div>
<div class="clear"></div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($batterytypeModel->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
	$('#batterytype-hide-link').on('click', function(event) {
		$('#batterytype-wrapper').hide();
	});
</script>
	