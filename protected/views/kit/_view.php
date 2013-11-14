<?php
/* @var $this KitController */
/* @var $data Kit */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('serial_num')); ?>:</b>
	<?php echo CHtml::encode($data->serial_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ref_num_id')); ?>:</b>
	<?php echo CHtml::encode($data->ref_num_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('anode_id')); ?>:</b>
	<?php echo CHtml::encode($data->anode_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cathode_id')); ?>:</b>
	<?php echo CHtml::encode($data->cathode_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kitter_id')); ?>:</b>
	<?php echo CHtml::encode($data->kitter_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kitting_date')); ?>:</b>
	<?php echo CHtml::encode($data->kitting_date); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('celltype_id')); ?>:</b>
	<?php echo CHtml::encode($data->celltype_id); ?>
	<br />

	*/ ?>

</div>