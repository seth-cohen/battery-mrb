<?php
/* @var $this ChannelController */
/* @var $data Channel */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number')); ?>:</b>
	<?php echo CHtml::encode($data->number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cycler_id')); ?>:</b>
	<?php echo CHtml::encode($data->cycler_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_charge_rate')); ?>:</b>
	<?php echo CHtml::encode($data->max_charge_rate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_discharge_rate')); ?>:</b>
	<?php echo CHtml::encode($data->max_discharge_rate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('multirange')); ?>:</b>
	<?php echo CHtml::encode($data->multirange); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('in_use')); ?>:</b>
	<?php echo CHtml::encode($data->in_use); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('in_commission')); ?>:</b>
	<?php echo CHtml::encode($data->in_commission); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('min_voltage')); ?>:</b>
	<?php echo CHtml::encode($data->min_voltage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_voltage')); ?>:</b>
	<?php echo CHtml::encode($data->max_voltage); ?>
	<br />

	*/ ?>

</div>