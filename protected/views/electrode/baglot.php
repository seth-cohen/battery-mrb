<?php
/* @var $this ElectrodeController */
/* @var $model BaggingStats */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Electrodes'=>array('index'),
	'Bagging Electrode Lot',
);

$this->menu=array(
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
    array('label'=>'Calendar Electrode Lot', 'url'=>array('calendarlot')),
    array('label'=>'Blank Electrode Lot', 'url'=>array('blanklot')),
    array('label'=>'View All Electrodes', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

?>

<h1>Bag Electrode Lot</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bagelectrode-form',
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
			<?php echo CHtml::label('Cathode Lot Number', 'Electrode_id'); ?>
			<?php echo $form->DropDownList($model, 'electrode_id',
							CHtml::listData(Electrode::model()->cathodes()->notGeneric()->findAll(), 'id','lot_num'), 
							array(
								'prompt'=>' -Select Cathode Lot- ',
								'style'=>'width:152px',
							)); ?>
			<?php echo $form->error($model,'electrode_id'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'bagger_id'); ?>
			<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
				<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array( 
					'model'=>$model,
				 	'attribute'=>'bagger_search',
					'sourceUrl'=>$this->createUrl('/user/ajaxUserSearch'),
					'options' => array(
						'select'=>'js: 
							function(event, ui){
								$("#BaggingStats_bagger_id").attr("value", ui.item.id);
							}',
					),
					
				)); ?>
			<?php else: /* user can only create it as themselves */ ?>
				<?php echo CHtml::textField('bagger_search',User::getFullNameProper(Yii::app()->user->id), array(
							'disabled'=>true,
				));?>
			<?php endif; ?>
			<?php echo $form->error($model,'bagger_id'); ?>
			<?php echo $form->hiddenField($model, 'bagger_id'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'good_count'); ?>
			<?php echo $form->textField($model,'good_count', array()); ?>
			<?php echo $form->error($model,'good_count'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'reject_count'); ?>
			<?php echo $form->textField($model,'reject_count', array()); ?>
			<?php echo $form->error($model,'reject_count'); ?>
		</div>
	</div>	
	<div class="clear"></div>
	
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'bagging_date'); ?>
			<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$model,
			        'attribute'=>'bagging_date',
			    	'value'=>$model->bagging_date,
			        // additional javascript options for the date picker plugin
			        'options'=>array(
			            'showAnim'=>'slideDown',
			            'changeMonth'=>true,
			            'changeYear'=>true,
			            'dateFormat' => 'yy-mm-dd'
			        ),
			    ));
			?>
			<?php echo $form->error($model,'bagging_date'); ?>
		</div>
	</div>
	
	<div class="row buttons" style="clear:left;">
		<?php echo CHtml::submitButton('Save Bagging Info'); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div>

<script type="text/javascript">
$(function(){
//	$(document).on('blur','#BaggingStats_electrode_id', function(e){
//		performAjaxValidation($('#bagelectrode-form'), "electrode_id");
//	});
	
	$(document).on('blur','#BaggingStats_bagger_search', function(e){
		performAjaxValidation($('#bagelectrode-form'), "bagger_id");
	});
});

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
