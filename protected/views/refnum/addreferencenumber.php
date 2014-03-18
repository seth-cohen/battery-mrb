<?php
/* @var $this RefnumController */
/* @var $refnumModel RefNum */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
    'Reference Numbers',
    'Add Reference Number',
);
?>

<h1>Add New Reference Number</h1>
<div id="refnum-wrapper" style="padding-bottom:10px;">
<div class="form border" style="padding:15px;">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'ref-num-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($refnumModel); ?>

    <div class="row">
        <?php echo $form->labelEx($refnumModel,'number'); ?>
        <?php echo $form->textField($refnumModel,'number',array('size'=>50,'maxlength'=>50)); ?>
        <?php echo $form->error($refnumModel,'number'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($refnumModel->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>

<div class="shadow border" id="refnum-wrapper" style="margin:auto;"> 
<h2 style="text-align:center">Existing Reference Numbers </h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>"batterytypes-grid",
	'dataProvider'=>$refnumModel->search(),
	'filter'=>$refnumModel,
	'columns'=>array(
		'id',
		'number',
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>
</div>
