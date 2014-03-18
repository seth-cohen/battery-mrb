<?php
/* @var $this BatteryController */
/* @var $model Battery */
/* @var $form CActiveForm */
?>

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

	<?php echo $form->errorSummary($model); ?>

	<div class="clear"></div>
	<div class="left-form">
		<div class="row">
	        <?php echo $form->labelEx($model,'batterytype_id'); ?>
	        <?php echo $form->dropDownList($model, 'batterytype_id', 
								CHtml::listData(Batterytype::model()->findAll(), 'id','name'), 
								array(
									'disabled'=>'disabled',
									'prompt'=>' -Select Battery Type- ',
									'style'=>'width:152px',
									'options'=>Batterytype::getIdPartNums(),
								)); ?> <span id='part-num' style='margin-left:5px;'></span>
	        <?php echo $form->error($model,'batterytype_id'); ?>
	    </div>
	</div>

	<div class="right-form">
		<div class="row">
	        <?php echo $form->labelEx($model,'serial_num'); ?>
	        <?php echo $form->textField($model,'serial_num',array('size'=>20,'maxlength'=>50, 'disabled'=>'disabled')); ?>
	        <?php echo $form->error($model,'serial_num'); ?>
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
		<?php echo $form->textField($model,'eap_num',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'eap_num'); ?>
	</div>
</div>	
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php echo $form->labelEx($model,'assembler_id'); ?>
		<?php echo $form->dropDownList($model, 'assembler_id', User::model()->forList()); ?> 
		<?php echo $form->error($model,'assembler_id'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php echo $form->labelEx($model,'assembly_date'); ?>
		<?php
		    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
		        'model'=>$model,
		        'attribute'=>'assembly_date',
		    	'value'=>$model->assembly_date,
		        // additional javascript options for the date picker plugin
		        'options'=>array(
		            'showAnim'=>'slideDown',
		            'changeMonth'=>true,
		            'changeYear'=>true,
		            'dateFormat' => 'yy-mm-dd'
		        ),
		    ));
		?>
		<?php echo $form->error($model,'assembly_date'); ?>
	</div>
</div>
<div class="clear"></div>

<div class="left-form">
	<div class="row">
		<?php 
			$disabled = array();
			if($model->assembler_id == 1)
			{
				$disabled['disabled'] = 'disabled';
			}
		?>
		<?php echo $form->labelEx($model,'data_accepted'); ?>
		<?php echo $form->dropDownList($model, 'data_accepted', array("0"=>"No", "1"=>"Yes"), $disabled); ?>
		<?php echo $form->error($model,'data_accepted'); ?>
	</div>
</div>
<div class="right-form">
	<div class="row">
		<?php 
			$disabled = array();
			if($model->data_accepted != 1)
			{
				$disabled['disabled'] = 'disabled';
			}
		?>
		<?php echo $form->labelEx($model,'ship_date'); ?>
		<?php
		    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
		        'model'=>$model,
		        'attribute'=>'ship_date',
		    	'value'=>$model->ship_date,
		    	'htmlOptions'=>$disabled,
		        // additional javascript options for the date picker plugin
		        'options'=>array(
		            'showAnim'=>'slideDown',
		            'changeMonth'=>true,
		            'changeYear'=>true,
		            'dateFormat' => 'yy-mm-dd'
		        ),
		    ));
		?>
		<?php echo $form->error($model,'ship_date'); ?>
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

