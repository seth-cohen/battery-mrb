<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>


<p>You may navigate to the necessary action using the quick links below.</p>
<div class = "shadow border" style="padding:5px 5px 0 5px;">
	<div class=" link-list" id="mfg-link-list" style="width:33%; float:left;">
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
			<li><?php echo CHtml::link('Create New Kits (Multi)', array('kit/multicreate'));?></li>
			<li><?php echo CHtml::link('View All Kits', array('kit/create'));?></li>
		</ul>
		<b>CELLS</b>
		<ul>
			<li><?php echo CHtml::link('Stack Cells (Multi)', array('cell/multistackcells'));?></li>
			<li><?php echo CHtml::link('Inspect Cells (Multi)', array('cell/multiinspectcells'));?></li>
			<li><?php echo CHtml::link('Laser Weld Cells (Multi)', array('cell/multilasercells'));?></li>
			<li><?php echo CHtml::link('Fill Cells (Multi)', array('cell/multifillcells'));?></li>
			<li><?php echo CHtml::link('Fillport Weld Cells (Multi)', array('cell/multitipoffcells'));?></li>
			<li><?php echo CHtml::link('View All Cells', array('cell/index'));?></li>			
		</ul>
		<b>Batteries</b>
		<ul>
			<li><?php echo CHtml::link('Assemble Battery', array('battery/assemble'));?></li>
			<li><?php echo CHtml::link('Return Spares', array('battery/returnspares'));?></li>		
		</ul>
	</div>
	
	<div class=" link-list" id="testlab-links" style="width:33%; float:right;">
	<h3 style="text-align:center">Test Lab</h3>
		<b>CELL TESTING</b>
		<ul>
			<li><?php echo CHtml::link('Put Cells on Formation', array('testlab/cellformation'));?></li>
			<li><?php echo CHtml::link('View Cells on Formation', array('testlab/formationindex'));?></li>
			<li><?php echo CHtml::link('Put Cells on CAT', array('testlab/cellcat'));?></li>
			<li><?php echo CHtml::link('View Cells on CAT', array('testlab/catindex'));?></li>
			<li><?php echo CHtml::link('View All Cells on Test', array('testlab/cellindex'));?></li>
			<li><?php echo CHtml::link('Channel Reassignments', array('testlab/testreassignment'));?></li>
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
			<li><?php echo CHtml::link('Deliver Cells to Battery Assembly', array('testlab/deliverforassembly'));?></li>
			<li><?php echo CHtml::link('Move Cells to Storage', array('testlab/storage'));?></li>
			<li><?php echo CHtml::link('Handle Returned Spares', array('testlab/returnedspares'));?></li>
			<li><?php echo CHtml::link('Scrap Cells', array('cell/scrapcells'));?></li>
		</ul>
	</div>
	
	<div class="link-list" id="quality-links" style="padding:0 35% 5px 35%;">
	<h3 style="text-align:center">Engineering/Quality</h3>
		<b>NCRs</b>
		<ul>
			<li><?php echo CHtml::link('Put Cells on NCR', array('quality/ncr'));?></li>
			<li><?php echo CHtml::link('Dispo Cell NCRs', array('testlab/cellcat'));?></li>
		</ul>
		<b>Batteries</b>
		<ul>
			<li><?php echo CHtml::link('Cell Selection', array('battery/cellselection'));?></li>
			<li><?php echo CHtml::link('View All Batteries', array('battery/index'));?></li>
			<li><?php echo CHtml::link('Create New Battery Type', array('battery/newtype'));?></li>
		</ul>
	</div>
	<div style="clear:both"></div>
</div>
