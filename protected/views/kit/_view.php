<?php
/* @var $this KitController */
/* @var $data Kit */
?>

<div class="view">


	<b><?php echo CHtml::encode($data->getAttributeLabel('celltype')); ?>:</b>
	<?php echo CHtml::encode($data->celltype->name); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('serial_num')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->getFormattedSerial()), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ref_num_id')); ?>:</b>
	<?php echo CHtml::encode($data->ref_num_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('anodeIds')); ?>:</b>
	<?php echo $data->getAnodeList(); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cathodeIds')); ?>:</b>
	<?php echo $data->getCathodeList(0); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kitter_id')); ?>:</b>
	<?php echo CHtml::encode($data->kitter->getFullName()); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kitting_date')); ?>:</b>
	<?php echo CHtml::encode($data->kitting_date); ?>
	<br />


</div>