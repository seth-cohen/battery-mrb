<?php
/* @var $this CyclerController */
/* @var $data Cycler */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sy_number')); ?>:</b>
	<?php echo CHtml::encode($data->sy_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('num_channels')); ?>:</b>
	<?php echo CHtml::encode($data->num_channels); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cal_date')); ?>:</b>
	<?php echo CHtml::encode($data->cal_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cal_due_date')); ?>:</b>
	<?php echo CHtml::encode($data->cal_due_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('calibrator_id')); ?>:</b>
	<?php echo CHtml::encode($data->calibrator_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('maccor_job_num')); ?>:</b>
	<?php echo CHtml::encode($data->maccor_job_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('govt_tag_num')); ?>:</b>
	<?php echo CHtml::encode($data->govt_tag_num); ?>
	<br />

	*/ ?>

</div>