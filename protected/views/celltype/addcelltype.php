<?php
/* @var $this CelltypeController */
/* @var $celltypeModel Celltype */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
    'Cell Types',
    'Add Cell Type',
);
?>

<h1>Add New Cell Type </h1>
<div id="celltype-wrapper" style="padding-bottom:10px;">
<div class="form border" style="padding:15px;">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'celltype-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>true,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($celltypeModel); ?>

    <div class="row">
        <?php echo $form->labelEx($celltypeModel,'name'); ?>
        <?php echo $form->textField($celltypeModel,'name',array('size'=>50,'maxlength'=>50)); ?>
        <?php echo $form->error($celltypeModel,'name'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($celltypeModel->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>

<div class="shadow border" id="celltype-existing-wrapper" style="margin:auto;"> 
<h2 style="text-align:center">Existing Cell Types </h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>"batterytypes-grid",
	'dataProvider'=>$celltypeModel->search(),
	'filter'=>$celltypeModel,
	'columns'=>array(
		'id',
		'name',
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>
</div>
