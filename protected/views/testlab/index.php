<?php
/* @var $this TestlabController */

$this->breadcrumbs=array(
	'Testlab',
);
?>

<h1><?php echo ucfirst($this->id) . ' ' . ucfirst($this->action->id); ?></h1>

<div class=" link-list" id="testlab-link-list" style="width:47%; float:left;">
<h3 style="text-align:center">Test Lab Actions</h3>
	<b>CELL TESTING</b>
	<ul>
		<li><?php echo CHtml::link('Put Cells on Formation', array('testlab/cellformation'));?></li>
		<li><?php echo CHtml::link('Put Cells on CAT', array('testlab/cellcat'));?></li>
		<li><?php echo CHtml::link('View All Cells on Test', array('testlab/cellindex'));?></li>
	</ul>
	<b>EQUIPMENT</b>
	<ul>
		<li><?php echo CHtml::link('View Channels Status', array('channel/index'));?></li>
		<li><?php echo CHtml::link('Add New Test Chamber', array('chamber/create'));?></li>
		<li><?php echo CHtml::link('View All Test Chambers', array('chamber/index'));?></li>
		<li><?php echo CHtml::link('Add New Test Cycler', array('cycler/create'));?></li>
		<li><?php echo CHtml::link('View All Test Cyclers', array('cycler/index'));?></li>
	</ul>
	<b>STORAGE/DELIVERY</b>
	<ul>
		<li><?php echo CHtml::link('Deliver Cells to Battery Assembly', array('cell/multistackcells'));?></li>
		<li><?php echo CHtml::link('Move Cells to Storage', array('cell/multifillcells'));?></li>
		<li><?php echo CHtml::link('Scrap Cells', array('cell/multiinspectcells'));?></li>
	</ul>
</div>
