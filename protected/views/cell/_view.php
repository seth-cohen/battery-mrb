<?php
/* @var $this CellController */
/* @var $data Cell */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('serial_num')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->kit->celltype->name.'-'.$data->kit->serial_num), array('view', 'id'=>$data->id)); ?>
	<br />

	<b>Kitted:</b>
	<?php echo date('G:i \o\n n/j/y', strtotime($data->kit->kitting_date)); ?> [<?php echo CHtml::encode($data->kit->kitter->getFullName()); ?>]
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ref_num')); ?>:</b>
	<?php echo CHtml::encode($data->ref_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('eap_num')); ?>:</b>
	<?php echo CHtml::encode($data->eap_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('celltype_id')); ?>:</b>
	<?php echo CHtml::encode($data->kit->celltype->name); ?>
	<br />

	<b>Stacked:</b>
	<?php echo date('G:i \o\n n/j/y', strtotime($data->stack_date)); ?> [<?php echo CHtml::encode($data->stacker->getFullName()); ?>]
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('stack_date')); ?>:</b>
	<?php echo CHtml::encode($data->stack_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dry_wt')); ?>:</b>
	<?php echo CHtml::encode($data->dry_wt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wet_wt')); ?>:</b>
	<?php echo CHtml::encode($data->wet_wt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('filler_id')); ?>:</b>
	<?php echo CHtml::encode($data->filler_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fill_date')); ?>:</b>
	<?php echo CHtml::encode($data->fill_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inspector_id')); ?>:</b>
	<?php echo CHtml::encode($data->inspector_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inspection_date')); ?>:</b>
	<?php echo CHtml::encode($data->inspection_date); ?>
	<br />

	*/ ?>

</div>