<?php
/* @var $this ElectrodeController */
/* @var $model Electrode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Electrodes'=>array('index'),
	'Calendar Electrode Lot',
);

$this->menu=array(
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
    array('label'=>'Blank Electrode Lot', 'url'=>array('blanklot')),
    array('label'=>'Bag Cathode Lot', 'url'=>array('baglot')),
    array('label'=>'View All Electrodes', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Calendar Electrode Lot</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'calelectrode-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="left-form">	
		<div class="row multidrop5">
			<?php echo $form->labelEx($model, 'lot_num'); ?>
			<?php echo $form->dropDownList($model,'id',
							CHtml::listData(Electrode::model()->notGeneric()->findAll(), 'id','lot_num'), 
							array(
								'prompt'=>' -Select Electrode Lot- ',
								'onChange'=>'lotSelected(this)',
								'style'=>'width:152px',
							)); ?>
			<?php echo $form->error($model,'lot_num'); ?>
			<?php echo $form->hiddenField($model, 'lot_num'); ?>
		</div>
	</div>
	<div class="right-form">	
		<div class="row">
			<?php echo $form->labelEx($model,'cal_id'); ?>
			<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
				<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array( 
					'model'=>$model,
				 	'attribute'=>'cal_search',
					'sourceUrl'=>$this->createUrl('/user/ajaxUserSearch'),
					'options' => array(
						'select'=>'js: 
							function(event, ui){
								$("#Electrode_cal_id").attr("value", ui.item.id);
							}',
					),
					
				)); ?>
			<?php else: /* user can only create it as themselves */ ?>
				<?php echo CHtml::textField('cal_search',User::getFullNameProper(Yii::app()->user->id), array(
							'disabled'=>true,
				));?>
			<?php endif; ?>
			<?php echo $form->error($model,'cal_id'); ?>
			<?php echo $form->hiddenField($model, 'cal_id'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'cal_date'); ?>
			<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$model,
			        'attribute'=>'cal_date',
			    	'value'=>$model->cal_date,
			        // additional javascript options for the date picker plugin
			        'options'=>array(
			            'showAnim'=>'slideDown',
			            'changeMonth'=>true,
			            'changeYear'=>true,
			            'dateFormat' => 'yy-mm-dd'
			        ),
			    ));
			?>
			<?php echo $form->error($model,'cal_date'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'thickness'); ?>
			<?php echo $form->textField($model,'thickness', array()); ?>
			<?php echo $form->error($model,'thickness'); ?>
		</div>
	</div>	
	<div class="clear"></div>
	
	
	<div class="row buttons" style="clear:left;">
		<?php echo CHtml::submitButton('Save Calendar Info'); ?>
	</div>
	
<?php $this->endWidget(); ?>
</div><!-- form -->

<script type="text/javascript">
$(function(){
	$(document).on('blur','#Electrode_id', function(e){
		performAjaxValidation($('#calelectrode-form'), "lot_num");
	});
	
	$(document).on('blur','#Electrode_cal_search', function(e){
		performAjaxValidation($('#calelectrode-form'), "cal_id");
	});
});

	
function lotSelected(sel)
{
	var lot_num = $('option:selected', $(sel)).text();
	$('#Electrode_lot_num').val(lot_num);
	
	var lot_id = $('option:selected', $(sel)).val();
	if(lot_id == ''){
		$('#Electrode_lot_num').val('');
	}
	
	$.ajax({
		type:'get',
		url: '<?php echo $this->createUrl('electrode/ajaxlotdetails'); ?>',
		data:
		{
			lot_id: lot_id.toString(),
		},
		success: function(data){
			if (data == '0')
			{
				$('errorSummary').remove();
			}
			else
			{
				try
		    	{
			    	var details = $.parseJSON(data);
			    	if(details.cal_date != "0000-00-00"){
				    	alert('Cathode Lot ' + lot_num + ' has already been calendared.')
		    	   		$('#Electrode_cal_date').val(details.cal_date);
			    	}
			    	else{
			    		$('#Electrode_cal_date').val('<?php echo date('Y-m-d'); ?>');
			    	}
	    	   		if(details.thickness != null)
	    	   			$('#Electrode_thickness').val(details.thickness);
	    	   		else{
	    	   			$('#Electrode_thickness').val('');
	    	   		}
	    	   		
<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
	    	   		if(details.cal_operator != null)
	    	   			$('#Electrode_cal_search').val(details.cal_operator);
	    	   		else{
	    	   			$('#Electrode_cal_search').val('');
	    	   		}
	    	   		if(details.cal_id != null)
	    	   			$('#Electrode_cal_id').val(details.cal_id);
	    	   		else{
	    	   			$('#Electrode_cal_id').val('');
	    	   		}
<?php endif; ?>
		    	}
		    	catch(e)
		    	{
		    		$('#bagelectrode-form').prepend(data);
		    		console.log(e.message);
		    	}
			}
		},
	});
}

performAjaxValidation = function($form, attribute){
    var settings = $form.data("settings");
    $.each(settings.attributes, function () {
    	if (this.name == attribute){
        	this.status = 2;
    	}
    });
    $form.data("settings", settings);

    // trigger ajax validation
    $.fn.yiiactiveform.validate($form, function (data) {
        $.each(settings.attributes, function () {
        	if (this.status == 2){
            	$.fn.yiiactiveform.updateInput(this, data, $form);
        	}
        });
    });
}
</script>
