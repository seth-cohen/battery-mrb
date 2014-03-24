<div class=" link-list" id="testlab-link-list" style="width:47%; float:left;">
	<b>CELL TESTING</b>
	<ul>
		<li><?php echo CHtml::link('Put Cells on Formation', array('testlab/cellformation'));?></li>
		<li><?php echo CHtml::link('View Cells on Formation', array('testlab/formationindex'));?></li>
		<li><?php echo CHtml::link('Put Cells on CAT', array('testlab/cellcat'));?></li>
		<li><?php echo CHtml::link('View Cells on CAT', array('testlab/catindex'));?></li>
		<li><?php echo CHtml::link('Condition for Assembly', array('testlab/cellconditioning'));?></li>
		<li><?php echo CHtml::link('View Cells Conditioning', array('testlab/conditioningindex'));?></li>
		<li><?php echo CHtml::link('Miscellaneous Testing', array('misctesting'));?></li>
		<li><?php echo CHtml::link('View Miscellaneous Tests', array('miscindex'));?></li>
		<li><?php echo CHtml::link('Test Reassignments', array('testlab/testreassignment'));?></li>
		<li><?php echo CHtml::link('View All Tests (Historic)', array('testindex'));?></li>
	</ul>
	<b>STORAGE/DELIVERY</b>
	<ul>
		<li><?php echo CHtml::link('Move Cells to Storage', array('testlab/storage'));?></li>
		<li><?php echo CHtml::link('Deliver Cells to Assembly', array('testlab/deliverforbattery'));?></li>
	</ul>
	<b>EQUIPMENT</b>
	<ul>
		<li><?php echo CHtml::link('View All Channels', array('channel/index'));?></li>
		<li><?php echo CHtml::link('Add New Test Chamber', array('chamber/create'));?></li>
		<li><?php echo CHtml::link('View All Test Chambers', array('chamber/index'));?></li>
		<li><?php echo CHtml::link('Add New Test Cycler', array('cycler/create'));?></li>
		<li><?php echo CHtml::link('View All Test Cyclers', array('cycler/index'));?></li>
	</ul>
</div>
