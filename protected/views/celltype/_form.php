<?php
/* @var $this CelltypeController */
/* @var $celltypeModel Celltype */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'celltype-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($celltypeModel); ?>

    <div class="row">
        <?php echo $form->labelEx($celltypeModel,'name'); ?>
        <?php echo $form->textField($celltypeModel,'name',array('size'=>50,'maxlength'=>50)); ?>
        <?php echo $form->error($celltypeModel,'name'); ?>
    </div>


<?php $this->endWidget(); ?>

</div><!-- form -->