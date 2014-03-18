<div class=" link-list" id="quality-link-list" style="width:47%; float:left;">
	<b>CELLS</b>
	<ul>
		<li><?php echo CHtml::link('Inspect Cells', array('cell/multiinspectcells'));?></li>
		<li style="margin-left:40px"><?php echo CHtml::link('Accept CAT Data', array('cell/multiacceptcatdata'));?></li>
		<li><?php echo CHtml::link('View All Cells', array('cell/index'));?></li>
	</ul>
	<b>BATTERIES</b>
	<ul>
		<li><?php echo CHtml::link('Battery Cell Selections', array('battery/cellselection'));?></li>
		<li><?php echo CHtml::link('Accept Test Data', array('battery/accepttestdata'));?></li>
		<li><?php echo CHtml::link('Ship Batteries', array('battery/ship'));?></li>
		<li><?php echo CHtml::link('Add Battery Type', array('battery/addbatterytype'));?></li>
		<li><?php echo CHtml::link('View All Batteries', array('battery/index'));?></li>
	</ul>
	<b>NCRs</b>
	<ul>
		<li><?php echo CHtml::link('Put Cells on NCR', array('ncr/putcellsonncr'));?></li>
		<li><?php echo CHtml::link('Dispo Cells on NCR', array('ncr/dispositioncells'));?></li>
		<li><?php echo CHtml::link('View All NCRs', array('ncr/index'));?></li>
	</ul>
	<b>General</b>
	<ul>
		<li><?php echo CHtml::link('Add Reference Number', array('ncr/putcellsonncr'));?></li>
	</ul>
</div>
