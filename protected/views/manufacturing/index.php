<?php
/* @var $this ManufacturingController */

$this->breadcrumbs=array(
	'Manufacturing',
);
?>
<h1><?php echo ucfirst($this->id). ' ' . ucfirst($this->action->id); ?></h1>


<p>You may navigate to the necessary action using the quick links below.</p>
<div class=" link-list" id="mfg-link-list" style="width:47%; float:left;">
<h3 style="text-align:center">Manufacturing Actions</h3>
	<b>ELECTRODES</b>
	<ul>
		<li style="margin-left:40px"><?php echo CHtml::link('Create New Electrode Coating Lot', array('electrode/create'));?></li>
		<li><?php echo CHtml::link('Calender Electrode Lot', array('electrode/calender'));?></li>
		<li><?php echo CHtml::link('Bag Electrode Lot', array('electrode/bag'));?></li>
		<li><?php echo CHtml::link('View All Electrode Lots', array('electrode/index'));?></li>
	</ul>
	<b>KITS</b>
	<ul>
		<li><?php echo CHtml::link('Create A New Kit', array('kit/create'));?></li>
		<li><?php echo CHtml::link('View All Kits', array('kit/create'));?></li>
	</ul>
	<b>CELLS</b>
	<ul>
		<li><?php echo CHtml::link('Stack Cells (Multi)', array('cell/multistackcells'));?></li>
		<li><?php echo CHtml::link('Fill Cells (Multi)', array('cell/multifillcells'));?></li>
		<li><?php echo CHtml::link('Inspect Cells (Multi)', array('cell/multiinspectcells'));?></li>
		<li><?php echo CHtml::link('View All Cells', array('cell/index'));?></li>
	</ul>
</div>

