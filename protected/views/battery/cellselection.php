<?php
/* @var $this BatteryController */
/* @var $batteryModel Battery */
/* @var $batterytypeModel Batterytype */

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
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/styles.css')
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
    
    <div class="row">
        <?php echo $form->labelEx($batteryModel,'refnum_search'); ?> 
        <?php echo $form->textField($batteryModel,'refnum_search',array('size'=>64,'maxlength'=>75)); ?>
        <span style="padding-left:10px"; ><em><b>NOTE:</b></em> Comma or Space delimited</span>
        <?php echo $form->error($batteryModel,'refnum_search'); ?>
    </div>
    
	<div class="row buttons">
		<?php echo CHtml::submitButton($batteryModel->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

	<div id="cell-selection-form"></div>
<?php $this->endWidget(); ?>
</div>

<div id="selection-container" style="overflow-x:hidden; position:relative;"></div>

<script type="text/javascript">
var currentPage = 0;

$(document).ready(function($) {

	// show the battery type form if there were errors in creating new model
	if (!($('#batterytype-form_es_').css('display') == 'none') )
	{
		$('#batterytype-wrapper').show();
	}

	
	$('#batterytype-link').on('click', function(event) {
		$('#batterytype-wrapper').show();
	});

	$(document).on('click', '#next-module-link', function(event){
		if (!$('#cellselection-wrapper-'+(currentPage+1)).length){
			//do nothing
		} else {
			if (!$('#cellselection-wrapper-'+(currentPage+2)).length){
				//do nothing
				$('#next-module-link').hide();
			}
			if(currentPage == 0)
				$('#previous-module-link').show();
			
			//animate current grid left
			var $element = $('#cellselection-wrapper-'+currentPage);
			var right = $element.parent().width()+20;
			$element.animate({
				right: right,
			});
			currentPage += 1;
			
			//animate next grid left to center
			var right = $element.parent().width()/2-$element.width()/2;
			$element = $('#cellselection-wrapper-'+currentPage);
			$element.animate({
				right: right,
			});
		}
	});

	$(document).on('click', '#previous-module-link', function(event){
		if (!$('#cellselection-wrapper-'+(currentPage-1)).length){
			//do nothing
			$('#previous-module-link').hide();
		} else {
			if (!$('#cellselection-wrapper-'+(currentPage-2)).length){
				//do nothing
				$('#previous-module-link').hide();
			}
			if(!$('#cellselection-wrapper-'+(currentPage+1)).length)
				$('#next-module-link').show();
			
			//animate current grid left
			var $element = $('#cellselection-wrapper-'+currentPage);
			var right = -$element.parent().width()-20;
			$element.animate({
				right: right,
				position: "absolute",
			});
			currentPage -= 1;
			
			//animate next grid left to center
			var right = $element.parent().width()/2-$element.width()/2;
			$element = $('#cellselection-wrapper-'+currentPage);
			$element.animate({
				right: right,
			});
		}
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
		url: '<?php echo $this->createUrl('battery/ajaxtypeselected'); ?>',
		data:
		{
			type_id: type_id.toString(),
		},
		success: function(data){
			currentPage = 0;
			$('#selection-container').html(data).css('height','600px');
			$('#previous-module-link').hide();
			if (!$('#cellselection-wrapper-'+(currentPage+1)).length){
				//do nothing
				$('#next-module-link').hide();
			}
		},
	});
}
</script>