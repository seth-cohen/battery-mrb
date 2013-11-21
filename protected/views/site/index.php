<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>


<p>You may navigate to the necessary action using the quick links below.</p>
<div class=" link-list" id="mfg-link-list" style="width:47%; float:left;">
<h3 style="text-align:center">Manufacturing</h3>
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

<div class=" link-list" id="testlab-link-list" style="width:47%; float:right;">
<h3 style="text-align:center">Test Lab</h3>
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
<div style="clear:both"></div>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>
