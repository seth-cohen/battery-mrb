<?php
/* @var $this ManufacturingController */
/* @var $model Electrode */
/* @var $form CActiveForm */
?>

<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'createelectrode-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'is_anode'); ?>
			<?php echo CHtml::activeDropDownList($model, 'is_anode', 
							array('1'=>'Anode', '0'=>'Cathode'), 
							array(
								'prompt'=>' -Select Electrode Type- ',
								'style'=>'width:152px'
							)); ?>
			<?php echo $form->error($model,'is_anode'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'lot_num'); ?>
			<?php echo $form->textField($model,'lot_num', array('disabled'=>'disabled')); ?> 
			<span style="padding-left:10px"; ><em><b>NOTE:</b></em> It is day <b><?php echo date('z');?></b> of <?php echo date('Y'); ?></span>
			<?php echo $form->error($model,'lot_num'); ?>
		</div>
	</div>	
	<div class="clear"></div>
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'ref_num_id'); ?>
			<?php echo CHtml::activeDropDownList($model, 'ref_num_id', 
							CHtml::listData(RefNum::model()->findAll(array('condition'=>'id <> 70')), 'id','number'), 
							array(
								'prompt'=>' -Select Reference No.- ',
								'onchange'=>'refSelected(this)',
								'style'=>'width:152px'
							)); ?>
			<?php echo $form->error($model,'ref_num_id'); ?>
		</div>
	</div>	
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'eap_num'); ?>
			<?php echo $form->textField($model,'eap_num', array(
							//'onfocus'=>'this.value = this.value;',
						)); ?> <span style="padding-left:10px"; ><em><b>ex.</b></em> EAP 00999 ADD 1A</span>
			<?php echo $form->error($model,'eap_num'); ?>
		</div>
	</div>	
	<div class="clear"></div>
	
	<div class="left-form">	
		<div class="row">
			<?php echo $form->labelEx($model,'coater_id'); ?>
			<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
				<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array( 
					'model'=>$model,
				 	'attribute'=>'coater_search',
					'sourceUrl'=>$this->createUrl('/user/ajaxUserSearch'),
					'options' => array(
						'select'=>'js: 
							function(event, ui){
								$("#Electrode_coater_id").attr("value", ui.item.id);
							}',
					),
					
				)); ?>
			<?php else: /* user can only create it as themselves */ ?>
				<?php echo $form->textField($model, 'coater_search',array(
							'disabled'=>true,
				));?>
			<?php endif; ?>
			<?php echo $form->hiddenField($model, 'coater_id'); ?>
		</div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo $form->labelEx($model,'coat_date'); ?>
			<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$model,
			        'attribute'=>'coat_date',
			    	'value'=>$model->coat_date,
			        // additional javascript options for the date picker plugin
			        'options'=>array(
			            'showAnim'=>'slideDown',
			            'changeMonth'=>true,
			            'changeYear'=>true,
			            'dateFormat' => 'yy-mm-dd'
			        ),
			    ));
			?>
			<?php echo $form->error($model,'coat_date'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="left-form">
		<div class="row">
			<?php echo $form->labelEx($model,'thickness'); ?>
			<?php echo $form->textField($model,'thickness', array()); ?>
			<?php echo $form->error($model,'thickness'); ?>
		</div>
	</div>	
	<div class="right-form">
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
	<div class="clear"></div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'moisture'); ?>
		<?php echo $form->textField($model,'moisture', array()); ?>
		<?php echo $form->error($model,'moisture'); ?>
	</div>
		
	<div class="row buttons" style="clear:left;">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>
	

</div><!-- form -->

<script type="text/javascript">
	function refSelected(sel)
	{
		var ref = $('option:selected', $(sel)).text();
		$("#Electrode_eap_num").attr("value","EAP "+ ref + " ADD ").focus();
		//$("#Electrode_eap_num").focus();
	}
</script>
	