<?php
/* @var $this TestLabController */
/* @var $model TestAssignment */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'Update Assignment',
);

$this->menu=array(
	array('label'=>'Put cells on Formation', 'url'=>array('cellformation')),
	array('label'=>'View Cells on Formation', 'url'=>array('formationindex')),
	array('label'=>'Put cells on CAT', 'url'=>array('cellcat')),
	array('label'=>'View Cells on CAT', 'url'=>array('catindex')),
	array('label'=>'Condition for Assembly', 'url'=>array('cellconditioning')),
	array('label'=>'View Cells Conditioning', 'url'=>array('conditioningindex')),
	array('label'=>'Miscellaneous Testing', 'url'=>array('misctesting')),
	array('label'=>'View Miscellaneous Tests', 'url'=>array('miscindex')),
	array('label'=>'View This Assignment', 'url'=>array('viewassignment','id'=>$model->id)),
	array('label'=>'Test Reassignments', 'url'=>array('testreassignment')),
	array('label'=>'Move Cells to Storage', 'url'=>array('storage')),
	array('label'=>'Deliver Cells to Assembly', 'url'=>array('deliverforbattery')),
	array('label'=>'View All Tests (Historic)', 'url'=>array('testindex')),
	array('label'=>'View All Cells', 'url'=>array('/cell/index')),
);

/* ionclude JQuery scripts to allow for autocomplte */
Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl().
        '/jui/css/base/jquery-ui.css'
);

var_dump($model->channel_id);
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'testassignment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="left-form">
		<div class="row">
			<?php echo CHtml::label('Cell Serial', 'Cell[serial_num]'); ?>
			<?php echo CHtml::textField('Cell[serial_num]',$model->cell->kit->getFormattedSerial(),array('size'=>20,'maxlength'=>50, 'disabled'=>'disabled')); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'chamber_id'); ?>
			<?php echo $form->dropDownList($model, 'chamber_id', Chamber::forList(), array('options'=>Chamber::getTextColor())); ?>
			<?php echo $form->error($model,'chamber_id'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">
		<div class="row">
			<?php echo CHtml::label('Cycler','Cycler'); ?>
			<?php echo CHtml::dropDownList('Cycler', $model->channel->cycler_id, Cycler::forList() , array(
				'class'=>'cycler-dropdown',
				'onChange'=>'cycSelected(this)',
				'style'=>'width:100px',
				'data-original'=>$model->channel->cycler->id,
			));?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'channel_id'); ?>
			<?php echo $form->dropDownList($model, 'channel_id', array(), array(
				'class'=>'channel-dropdown',
				'data-channel-id'=>$model->channel_id,
				'data-channel-number'=>$model->channel->number,
			)); ?>
			<?php echo $form->error($model,'channel_id'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'test_start'); ?>
			<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$model,
			        'attribute'=>'test_start',
			    	'value'=>$model->test_start,
			        // additional javascript options for the date picker plugin
			        'options'=>array(
			            'showAnim'=>'slideDown',
			            'changeMonth'=>true,
			            'changeYear'=>true,
			            'dateFormat' => 'yy-mm-dd',
			        ),
			        'htmlOptions'=>array(
						'style'=>'width:150px;',
					),
			    ));
			?>
			<?php echo $form->error($model,'test_start'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo CHtml::label('Start Time','start_time'); ?>
			<?php echo CHtml::textField('start_time',date('H:i', $model->test_start_time)); ?>
			<?php echo $form->error($model,'channel_id'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'is_active'); ?>
			<?php echo $form->dropDownList($model, 'is_active', array("0"=>"No", "1"=>"Yes"), array()); ?>
			<?php echo $form->error($model,'is_active'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php 
				$testType;
				if ($model->is_formation) $testType = 0;
				elseif($model->is_conditioning) $testType = 2;
				else $testType = 1;
			?>
			<?php echo CHtml::label('Test Type','test_type'); ?>
			<?php 
				echo CHtml::radioButtonList('test_type',$testType,
					array(
						0=>'Formation',
						1=>'CAT',
						2=>'Conditioning',
						3=>'Misc',
					)
				); ?>
			<?php echo $form->error($model,'channel_id'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'operator_id'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array( 
					'model'=>$model,
				 	'attribute'=>'operator_search',
					'sourceUrl'=>$this->createUrl('/user/ajaxUserSearch'),
					'options' => array(
						'select'=>'js: 
							function(event, ui){
								$("#TestAssignment_operator_id").attr("value", ui.item.id);
							}',
					),
					'htmlOptions'=>array(
						'style'=>'width:152px;',
					),
				)); ?>
			<?php echo $form->error($model,'operator_id'); ?>
			<?php echo $form->hiddenField($model, 'operator_id'); ?>
		</div>
	</div>

<div class="clear"></div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Add' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
$(function(){ // document.ready....
	cycSelected($('#Cycler').get(0) );
});

function cycSelected(sel)
{
	var cycler_id = $('option:selected', $(sel)).attr("value");

	$.ajax({
		url: '<?php echo $this->createUrl('/cycler/ajaxchannellist'); ?>',
		type: 'POST',
		data: 
		{
			id: cycler_id,
		},
		success: function(data) {
			var el = $('.channel-dropdown');
			el.attr('disabled',false);
			el.html(data);
			
			if($(sel).val() == $(sel).data('original')){
				$('option[value=""]', el).remove();
				if($('option[value="<?php echo $model->channel_id;?>"]', el).length <= 0)
				{
					el.prepend($('<option>', {value: el.data('channel-id')})
							.text(el.data('channel-number')));
				}
					el.val(el.data('channel-id'));
				
				el.data('prevValue', el.val());
				el.data('prevText', $('option:selected', el).attr('value'));
			}
			
			$('.channel-dropdown').val(<?php echo $model->channel_id;?>);
		},
	});	
}
</script>