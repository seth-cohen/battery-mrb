<?php
/* @var $this ElectrodeController */
/* @var $electrodeModel Battery */

$this->breadcrumbs=array(
	'Electrodes'=>array('index'),
	'Upload Electrodes',
);

$this->menu=array(
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
    array('label'=>'Calendar Electrode Lot', 'url'=>array('calendarlot')),
    array('label'=>'Blank Electrode Lot', 'url'=>array('blanklot')),
    array('label'=>'Bag Cathode Lot', 'url'=>array('baglot')),
    array('label'=>'Electrode Index', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Upload Electrodes From File</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'electrode-form',
	'action'=>$this->createUrl('uploadelectrodes'),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array(
		'enctype'=>"multipart/form-data",
	),
)); ?>

<div class="form">
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($electrodeModel); ?>
	
	<div class="row"  id="upload-wrapper">
          Please choose a file: <br/>
          <input name="Uploaded" type="file" /><br />
   	 </div>
    
    <div class="row buttons">
		<?php echo CHtml::submitButton('Upload', array('id'=>'upload-button')); ?>
	</div>
    
</div><!-- Form div -->

<?php $this->endWidget(); ?>
