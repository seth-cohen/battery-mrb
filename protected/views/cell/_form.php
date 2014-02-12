<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $kit Kit */
/* @var $celltype Celltype */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cell-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($kit,'serial_num'); ?>
		<?php echo $form->dropDownList($kit, 'celltype_id', $celltype->forList()); ?> <?php echo $form->textField($kit,'serial_num',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($kit,'serial_num'); ?>
	</div>

<div class="left-form">
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
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'eap_num'); ?>
		<?php echo $form->textField($model,'eap_num',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'eap_num'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'stacker_id'); ?>
		<?php echo $form->dropDownList($model, 'stacker_id', User::model()->forList()); ?> 
		<?php echo $form->error($model,'stacker_id'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'stack_date'); ?>
		<?php
		    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
		        'model'=>$model,
		        'attribute'=>'stack_date',
		    	'value'=>$model->stack_date,
		        // additional javascript options for the date picker plugin
		        'options'=>array(
		            'showAnim'=>'slideDown',
		            'changeMonth'=>true,
		            'changeYear'=>true,
		            'dateFormat' => 'yy-mm-dd'
		        ),
		    ));
		?>
		<?php echo $form->error($model,'stack_date'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'dry_wt'); ?>
		<?php echo $form->textField($model,'dry_wt'); ?>
		<?php echo $form->error($model,'dry_wt'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'wet_wt'); ?>
		<?php echo $form->textField($model,'wet_wt'); ?>
		<?php echo $form->error($model,'wet_wt'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'filler_id'); ?>
		<?php echo $form->dropDownList($model, 'filler_id', User::model()->forList()); ?>
		<?php echo $form->error($model,'filler_id'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'fill_date'); ?>
		<?php
		    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
		        'model'=>$model,
		        'attribute'=>'fill_date',
		    	'value'=>$model->fill_date,
		        // additional javascript options for the date picker plugin
		        'options'=>array(
		            'showAnim'=>'slideDown',
		            'changeMonth'=>true,
		            'changeYear'=>true,
		            'dateFormat' => 'yy-mm-dd'
		        ),
		    ));
		?>
		<?php echo $form->error($model,'fill_date'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'inspector_id'); ?>
		<?php echo $form->dropDownList($model, 'inspector_id', User::model()->forList()); ?>
		<?php echo $form->error($model,'inspector_id'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'inspection_date'); ?>
		<?php
		    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
		        'model'=>$model,
		        'attribute'=>'inspection_date',
		    	'value'=>$model->inspection_date,
		        // additional javascript options for the date picker plugin
		        'options'=>array(
		            'showAnim'=>'slideDown',
		            'changeMonth'=>true,
		            'changeYear'=>true,
		            'dateFormat' => 'yy-mm-dd'
		        ),
		    ));
		?>
		<?php echo $form->error($model,'inspection_date'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row multidrop5">
		<?php echo CHtml::label('Anode Lots', 'Kit_anodeIds'); ?>
		<?php echo CHtml::DropDownList('Kit[anodeIds][]', $model->kit->anodeIds, 
						CHtml::listData(Electrode::model()->anodes()->findAll(), 'id','lot_num'), 
						array(
							'multiple'=>'multiple',
							'prompt'=>' -Select Anode Lots- ',
							'style'=>'width:152px',
							'size'=>5,
						)); ?>
		<?php echo $form->error($model,'anodeIds'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row multidrop5">
		<?php echo CHtml::label('Cathode Lots', 'Kit_cathodeIds'); ?>
		<?php echo CHtml::DropDownList('Kit[cathodeIds][]', $model->kit->cathodeIds, 
						CHtml::listData(Electrode::model()->cathodes()->findAll(), 'id','lot_num'), 
						array(
							'multiple'=>'multiple',
							'prompt'=>' -Select Cathode Lots- ',
							'style'=>'width:152px',
							'size'=>5,
						)); ?>
		<?php echo $form->error($model,'cathodeIds'); ?>
	</div>
</div>
<div class="clear"></div>

	<div class="row">
		<?php echo $form->labelEx($model,'location'); ?>
		<?php echo $form->textField($model,'location',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'location'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- form -->