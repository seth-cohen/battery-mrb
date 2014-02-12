<?php
/* @var $this BatteryController */
/* @var $batteryModel Battery */
/* @var $batterytypeModel Batterytype */
/* @var $sparesDataProvider  CArrayDataProvider */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	'Cell Selection',
);

$this->menu=array(
	array('label'=>'Assemble Battery', 'url'=>array('assemble')),
	array('label'=>'Accept Test Data', 'url'=>array('accepttestdata'), 'visible'=>Yii::app()->user->checkAccess('quality')),
	array('label'=>'Ship Batteries', 'url'=>array('ship')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

/*needed because can't ajax load the css file for the gridview in the selectionform */
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/styles.css');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.easing.1.3.js');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.easing.compatibility.js');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/cellselection.js');
?>

<h1>Battery Cell Selections</h1>
<p> NOTE: Don't see your battery type in the drop down? 
 <?php echo CHtml::link('Click here','#',array('id'=>'batterytype-link')) ?> to create a new battery type.
 </p>

<div id="batterytype-wrapper" style="display:none; padding-bottom:10px;">
	<?php $this->renderPartial('_addbatterytype', array('batterytypeModel'=>$batterytypeModel)); ?>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'battery-form',
	'action'=>$this->createUrl('uploadselection'),
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
									'onchange'=>'typeSelected(this,"'
											.$this->createUrl('battery/ajaxtypeselected')
											.'","'
											.$this->createUrl('battery/ajaxavailablecells')
											.'")',
									'style'=>'width:152px',
									'options'=>Batterytype::getIdPartNums(),
								)); ?> <span id='part-num' style='margin-left:5px;'></span>
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
    <div class="row">
       <div style="padding-top:2px; width:500px; margin:auto;"><span id="last-serial"></span></div>
    </div>
    
    <hr style="margin:0;height:0;border-top: 1px solid black"/>
	<div class="row">
		<?php echo CHtml::label('Upload a file for selections?', 'Upload');?>
        <?php echo CHtml::checkBox('Upload', false, array());?>
    </div>
	<div class="row"  id="upload-wrapper" style='display:none'>
		<?php echo CHtml::image(Yii::app()->baseUrl.'/images/csv.jpg', 'Example File', array('style'=>'float:right;top:-45px;position:relative'))?>
          Please choose a file: <br/>
          <input name="Uploaded" type="file" /><br />
          <p style="padding:5px;color:blue;"><i>Note:  The file must be a CSV file and have the cell position as the first column and the cell serial number
          as the second column.  The serial number should be the full cell serial number including
          the cell type separated by the serial number by a hyphen '-'. [NCP55-6-XXXX or LiBC16DV-1-XXXX]<br/><br/>
          Any other columns will be ignored. Position number for spares should be s1 through 
          sN with N being the number of spares and listed in order of preference.</i></p>
   	 </div>
    
    <div class="row buttons">
		<?php echo CHtml::submitButton('Upload', array('style'=>'display:none', 'id'=>'upload-button')); ?>
	</div>
    
    <?php echo CHtml::ajaxSubmitButton('Filter',array('battery/cellselection'), array(),array("style"=>"display:none;")); ?>
	<?php echo CHtml::ajaxSubmitButton('Submit',array('battery/ajaxselection'), array('success'=>'checkSelection'), array("id"=>"submit-button")); ?>
    
</div><!-- Form div -->

<div id="selection-container" style="overflow-x:hidden; position:relative;margin-top: 12px; display:none;"></div>


<div class="shadow border" id="cellspares-wrapper" style="display:none; margin:auto; width:30%;"> 
<div style="text-align:center; width: 100%; font-size:1.2em;">SPARES</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>"cellspares-grid",
	'template'=>'{items}',
	'dataProvider'=>$sparesDataProvider,
	'columns'=>array(
		array(
			'name'=>'Cell No.',
			'value'=>'$data["id"]',
		),
		array(
			'header'=>'Cell Serial',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::dropDownList('Battery[Spares]['.$data['id'].'][id]', '', array(),array(
						'prompt'=>'-N/A-',
						'class'=>'cell-dropdown spares',
						'onchange'=>'spareSelected(this)',
						'style'=>'width:150px',
				));
			},
		),
/*		array(
			'header'=>'For Module',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Battery[Spares]['.$data['id'].'][module]', '', array(
						'style'=>'width:75px;',
				));
			},
		),*/
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
	urlSuccess = ' <?php echo $this->createUrl('battery/index') ?> ';

$(function(){
	if ($('#Upload').is(":checked")){
		$('#upload-wrapper').show();
		$('#upload-button').show(); 
		$('#submit-button').hide();
	}
	
	$(document).on('change','#Upload', function(){
		if ($('#Upload').is(":checked")){
			$('#upload-wrapper').show();
			$('#upload-button').show();
			
			$('#selection-container').hide();
			$('#cellspares-wrapper').hide();
			$('#submit-button').hide();
		} else {
			$('#upload-wrapper').hide();
			$('#upload-button').hide();
			
			$('#selection-container').show();
			$('#submit-button').show();
			if( $('#Battery_batterytype_id').val() !='')
				$('#cellspares-wrapper').show();
		}
	});
});

</script>