<div class=" link-list" id="mfg-link-list" style="width:47%; float:left;">
	<b>ELECTRODES</b>
	<ul>
		<li style="margin-left:40px"><?php echo CHtml::link('Create New Electrode Lot', array('electrode/create'));?></li>
		<li><?php echo CHtml::link('Calender Electrode Lot',  array('electrode/calendarlot'));?></li>
		<li><?php echo CHtml::link('Blank Electrode Lot', array('electrode/blanklot'));?></li>
		<li><?php echo CHtml::link('Bag Electrode Lot', array('electrode/baglot'));?></li>
		<li><?php echo CHtml::link('View All Electrode Lots', array('electrode/index'));?></li>
	</ul>
	<b>KITS</b>
	<ul>
		<li><?php echo CHtml::link('Create New Kits', array('kit/multicreate'));?></li>
		<li><?php echo CHtml::link('View All Kits', array('kit/index'));?></li>
	</ul>
	<b>CELLS</b>
	<ul>
		<li><?php echo CHtml::link('Stack Cells', array('cell/multistackcells'));?></li>
		<li><?php echo CHtml::link('Attach Covers To Cells', array('cell/multiattachcells'));?></li>
		<li><?php echo CHtml::link('Inspect Cells', array('cell/multiinspectcells'));?></li>
		<li><?php echo CHtml::link('Laser Weld Cells', array('cell/multiinspectcells'));?></li>
		<li><?php echo CHtml::link('Fill Cells', array('cell/multifillcells'));?></li>
		<li><?php echo CHtml::link('Fillport Weld Cells', array('cell/multitipoffcells'));?></li>
		<li><?php echo CHtml::link('View All Cells', array('cell/index'));?></li>
	</ul>
	<b>Batteries</b>
	<ul>
		<li style="margin-left:40px"><?php echo CHtml::link('Assemble Battery', array('battery/assemble'));?></li>
	</ul>
</div>
