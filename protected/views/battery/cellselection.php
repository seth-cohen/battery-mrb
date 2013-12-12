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
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
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
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
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
    
    <div class="row">
        <?php echo $form->labelEx($batteryModel,'refnum_search'); ?> 
        <?php echo $form->textField($batteryModel,'refnum_search',array('size'=>64,'maxlength'=>75)); ?>
        <span style="padding-left:10px"; ><em><b>NOTE:</b></em> Comma or Space delimited</span>
        <?php echo $form->error($batteryModel,'refnum_search'); ?>
    </div>
    
    <?php echo CHtml::ajaxSubmitButton('Filter',array('battery/cellselection'), array(),array("style"=>"display:none;")); ?>
	<?php echo CHtml::ajaxSubmitButton('Submit',array('battery/ajaxselection'), array('success'=>'checkSelection'), array("id"=>"submit-button")); ?>
    
</div><!-- Form div -->

<div id="selection-container" style="overflow-x:hidden; position:relative;margin-top: 12px;"></div>


<div class="shadow border" id="cellspares-wrapper" style="display:none;"> 
<div style="text-align:center; width:100%; font-size:1.2em;">SPARES</div>
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
				return	CHtml::dropDownList('Battery[Spares][serials]['.$data['id'].']', '', array(),array(
						'prompt'=>'-N/A-',
						'class'=>'cell-dropdown',
						'onchange'=>'cellSelected(this)',
						'style'=>'width:150px',
				));
			},
		),
		array(
			'header'=>'For Module',
			'type'=>'raw',
			'value'=>function($data, $row) {
				return	CHtml::textField('Battery[Spares][modules]['.$data['id'].']', '');
			},
		),
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

</script>