<?php
/* @var $this ChamberController */
/* @var $data Chamber */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brand')); ?>:</b>
	<?php echo CHtml::encode($data->brand); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('model')); ?>:</b>
	<?php echo CHtml::encode($data->model); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serial_num')); ?>:</b>
	<?php echo CHtml::encode($data->serial_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('in_commission')); ?>:</b>
	<?php echo CHtml::encode($data->in_commission); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('govt_tag_num')); ?>:</b>
	<?php echo CHtml::encode($data->govt_tag_num); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('cycler_id')); ?>:</b>
	<?php echo CHtml::encode($data->cycler_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('min_temp')); ?>:</b>
	<?php echo CHtml::encode($data->min_temp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_temp')); ?>:</b>
	<?php echo CHtml::encode($data->max_temp); ?>
	<br />

	*/ ?>

</div>