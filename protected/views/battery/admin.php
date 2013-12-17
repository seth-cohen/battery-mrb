<?php
/* @var $this BatteryController */
/* @var $model Battery */

$this->breadcrumbs=array(
	'Batteries'=>array('index'),
	'Admin',
);

$this->menu=array(
	array('label'=>'Battery Cell Selections', 'url'=>array('cellselection')),
	array('label'=>'View All Batteries', 'url'=>array('index')),
	array('label'=>'Battery Admin', 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#battery-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Batteries</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<div class="border shadow">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'battery-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'batterytype_search',
			'value'=>'$data->batterytype->name',
		),
		array(
			'name'=>'refnum_search',
			'value'=>'$data->refNum->number',
		),
		array(
			'name'=>'serial_num',
			'value'=>'"SN: ".$data->serial_num',
		),
		'eap_num',
		array(
			'name'=>'assembler_search',
			'value'=>function($data, $row){
				$result = 'Not Yet Assembled';
				if ($data->assembler_id!=1){
					$result = $data->assembler->getFullName();
				}
				return $result;
			},
		),
		/*
		'assembly_date',
		'ship_date',
		'location',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
	'selectionChanged'=>'batterySelected',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); ?>
</div>

<div id="battery-details" class="shadow border" style="display:none"></div>

<script type="text/javascript">
	function batterySelected(target_id){
		var battery_id;
		battery_id = $.fn.yiiGridView.getSelection(target_id);		

		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('battery/ajaxgetbatterycells'); ?>',
    		data:
    		{
    			id: battery_id.toString(),
    		},
    		success: function(data){
        		if(data == 'hide')
        		{
        			$('#battery-details').hide();
        		}
        		else
        		{
        			$('#battery-details').show();
            		$('#battery-details').html(data);
        		}
    		},
    	});
	}
</script>
