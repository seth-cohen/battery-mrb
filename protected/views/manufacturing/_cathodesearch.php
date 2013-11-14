<?php
/* @var $this ManufacturingController */
/* @var $model Cathode */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>$this->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'id'); ?>
        <?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'lot_num'); ?>
        <?php echo $form->textField($model,'lot_num',array('size'=>50,'maxlength'=>50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'eap_num'); ?>
        <?php echo $form->textField($model,'eap_num',array('size'=>50,'maxlength'=>50)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'coater_id'); ?>
        <?php echo $form->textField($model,'coater_id',array('size'=>10,'maxlength'=>10)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->