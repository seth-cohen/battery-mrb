<?php
/* @var $this BatteryController */
/* @var $batteryModel Battery */
/* @var $batterytypeModel Batterytype */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Battery', 'url'=>array('index')),
	array('label'=>'Manage Battery', 'url'=>array('admin')),
);
?>

<h1>Battery Cell Selections</h1>
<p> NOTE: Don't see your battery type in the drop down? 
 <?php echo CHtml::link('Click here','#',array('id'=>'batterytype-link')) ?> to create a new battery type.
 </p>

<div id="batterytype-wrapper" style="display:none; padding-bottom:10px;">
	<?php $this->renderPartial('_addbatterytype', array('batterytypeModel'=>$batterytypeModel)); ?>
</div>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'battery-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($batteryModel); ?>
	
<div class="left-form">
	<div class="row">
        <?php echo $form->labelEx($batteryModel,'ref_num_id'); ?>
        <?php echo $form->dropDownList($batteryModel, 'ref_num_id', 
							CHtml::listData(RefNum::model()->findAll(), 'id','number'), 
							array(
								'prompt'=>' -Select Reference No.- ',
								'onchange'=>'refSelected(this)',
								'style'=>'width:152px'
							)); ?>
        <?php echo $form->error($batteryModel,'ref_num_id'); ?>
    </div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($batteryModel,'eap_num'); ?>
		<?php echo $form->textField($batteryModel,'eap_num',array('size'=>20,'maxlength'=>50)); ?>
		<?php echo $form->error($batteryModel,'eap_num'); ?>
	</div>
</div>

<div class="clear"></div>
<div class="left-form">
	<div class="row">
        <?php echo $form->labelEx($batteryModel,'batterytype_id'); ?>
        <?php echo $form->dropDownList($batteryModel, 'batterytype_id', 
							CHtml::listData(Batterytype::model()->findAll(), 'id','name'), 
							array(
								'prompt'=>' -Select Battery Type- ',
								'onchange'=>'typeSelected(this)',
								'style'=>'width:152px'
							)); ?>
        <?php echo $form->error($batteryModel,'batterytype_id'); ?>
    </div>
</div>

<div class="right-form">
	<div class="row">
        <?php echo $form->labelEx($batteryModel,'serial_num'); ?>
        <?php echo $form->textField($batteryModel,'serial_num',array('size'=>20,'maxlength'=>50)); ?>
        <?php echo $form->error($batteryModel,'serial_num'); ?>
    </div>
</div>
    
<div class="clear"></div>
    
	<div class="row buttons">
		<?php echo CHtml::submitButton($batteryModel->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<div id="cell-selection-form"></div>
<?php $this->endWidget(); ?>
</div>

<script type="text/javascript">
$(document).ready(function($) {

	// show the battery type form if there were errors in creating new model
	if (!($('#batterytype-form_es_').css('display') == 'none') )
	{
		$('#batterytype-wrapper').show();
	}

	
	$('#batterytype-link').on('click', function(event) {
		$('#batterytype-wrapper').show();
	});
});

function refSelected(sel)
{
	var ref = $('option:selected', $(sel)).text();
	$("#Battery_eap_num").val("EAP "+ ref + " ADD ").focus();
}

function typeSelected(sel)
{
	var type_id = $('option:selected', $(sel)).val();
	$.ajax({
		type:'get',
		url: '<?php echo $this->createUrl('kit/lastserial'); ?>',
		data:
		{
			type_id: type_id.toString(),
		},
		success: function(data){
			$('#last-serial').text(
					"Highest: " +
					celltype + "-" + data
			 );
		},
	});
}
</script>