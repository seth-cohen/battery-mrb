<?php
/* @var $this BatteryController */
/* @var $data Battery */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('batterytype_id')); ?>:</b>
	<?php echo CHtml::encode($data->batterytype_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ref_num_id')); ?>:</b>
	<?php echo CHtml::encode($data->ref_num_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('eap_num')); ?>:</b>
	<?php echo CHtml::encode($data->eap_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serial_num')); ?>:</b>
	<?php echo CHtml::encode($data->serial_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('assembler_id')); ?>:</b>
	<?php echo CHtml::encode($data->assembler_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('assembly_date')); ?>:</b>
	<?php echo CHtml::encode($data->assembly_date); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ship_date')); ?>:</b>
	<?php echo CHtml::encode($data->ship_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location')); ?>:</b>
	<?php echo CHtml::encode($data->location); ?>
	<br />

	*/ ?>

</div>