<?php
/* @var $this KitController */
/* @var $model Kit */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kit-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<table class="double-form"><tr>
	<td>
		<div class="row">
			<?php echo $form->labelEx($model,'celltype_id'); ?>
			<?php echo CHtml::activeDropDownList($model, 'celltype_id', 
							CHtml::listData(Celltype::model()->findAll(), 'id','name'), 
							array(
								'prompt'=>' -Select Cell Type- ',
								'style'=>'width:152px'
							)); ?>
			<?php echo $form->error($model,'celltype_id'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'ref_num_id'); ?>
			<?php echo CHtml::activeDropDownList($model, 'ref_num_id', 
							CHtml::listData(RefNum::model()->findAll(), 'id','number'), 
							array(
								'prompt'=>' -Select Reference No.- ',
								'onchange'=>'refSelected(this)',
								'style'=>'width:152px'
							)); ?>
			<?php echo $form->error($model,'ref_num_id'); ?>
		</div>
		<div class="row multidrop5">
			<?php echo CHtml::label('Anode Lots', 'Kit_anodeIds'); ?>
			<?php echo CHtml::DropDownList('Kit[anodeIds][]', $model->anodeIds, 
							CHtml::listData(Electrode::model()->anodes()->findAll(), 'id','lot_num'), 
							array(
								'multiple'=>'multiple',
								'prompt'=>' -Select Anode Lots- ',
								'style'=>'width:152px',
								'size'=>5,
							)); ?>
			<?php echo $form->error($model,'anodeIds'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'kitter_search'); ?>
			<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
				<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array( 
					'model'=>$model,
				 	'attribute'=>'kitter_search',
					'sourceUrl'=>$this->createUrl('/user/ajaxUserSearch'),
					'options' => array(
						'select'=>'js: 
							function(event, ui){
								$("#Kit_kitter_id").attr("value", ui.item.id);
							}',
					),
					'htmlOptions'=>array(
						'style'=>'width:152px;',
					),
				)); ?>
			<?php else: /* user can only create it as themselves */ ?>
				<?php echo CHtml::textField('kitter_search',User::getFullNameProper(Yii::app()->user->id), array(
							'disabled'=>true,
							'style'=>'width:152px;'
				));?>
			<?php endif; ?>
			
			<?php echo $form->error($model,'kitter_id'); ?>
			<?php echo $form->hiddenField($model, 'kitter_id'); ?>
		</div>

	</td>
	<td>
		<div class="row">
			<?php echo $form->labelEx($model,'serial_num'); ?>
			<?php echo $form->textField($model,'serial_num',array('size'=>50,'maxlength'=>50, 'style'=>'width:150px')); ?>
			<?php echo $form->error($model,'serial_num'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'eap_num'); ?>
			<?php echo $form->textField($model,'eap_num', array(
							'style'=>'width:150px;',
						)); ?> <span style="padding-left:10px"; ><em><b>ex.</b></em> EAP 00999 ADD 1A</span>
			<?php echo $form->error($model,'eap_num'); ?>
		</div>
		<div class="row multidrop5">
			<?php echo CHtml::label('Cathode Lots', 'Kit_cathodeIds'); ?>
			<?php echo CHtml::DropDownList('Kit[cathodeIds][]', $model->cathodeIds, 
							CHtml::listData(Electrode::model()->cathodes()->findAll(), 'id','lot_num'), 
							array(
								'multiple'=>'multiple',
								'prompt'=>' -Select Cathode Lots- ',
								'style'=>'width:152px',
								'size'=>5,
							)); ?>
			<?php echo $form->error($model,'cathodeIds'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'kitting_date'); ?>
			<?php
			    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        'model'=>$model,
			        'attribute'=>'kitting_date',
			    	'value'=>$model->kitting_date,
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
			<?php echo $form->error($model,'kitting_date'); ?>
		</div>
	</td>	
	</tr></table>

	<div class="row buttons" style="clear:left;">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
	function refSelected(sel)
	{
		var ref = $('option:selected', $(sel)).text();
		$("#Kit_eap_num").attr("value","EAP "+ ref + " ");
		$("#Kit_eap_num").focus();
	}
</script>