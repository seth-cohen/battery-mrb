<?php
/* @var $this BatteryController */
/* @var $batteryModel Battery */
/* @var $batterytypeModel Batterytype */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Battery', 'url'=>array('index')),
	array('label'=>'Manage Battery', 'url'=>array('admin')),
);
?>

<h1>Battery Cell Selections</h1>
<p> NOTE: Don't see your battery type in the drop down? 
 <?php echo CHtml::link('Click here','#',array('id'=>'batterytype-link')) ?> to create a new battery type.
 </p>

<div id="batterytype-wrapper" style="display:none">
<?php $this->renderPartial('_addbatterytype', array('batterytypeModel'=>$batterytypeModel)); ?>
</div>

<script type="text/javascript">
$(document).ready(function($) {

	// show the battery type form if there were errors in creating new model
	if (!($('#batterytype-form_es_').css('display') == 'none') )
	{
		$('#batterytype-wrapper').show();
	}

	
	$('#batterytype-link').on('click', function(event) {
		$('#batterytype-wrapper').show();
	});
});
</script>