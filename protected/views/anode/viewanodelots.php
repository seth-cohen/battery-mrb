<?php
/* @var $this ManufacturingController */
/* @var $model Anode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Anodes'=>array('viewanodelots'),
	'View Lots',
);

$this->menu=array(
	array('label'=>'Create Anode Lot', 'url'=>array('createcathodelot')),
	array('label'=>'Create Cathode Lot', 'url'=>array('createanodelot')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#anode-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>All Anode Lots</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_anodesearch',array(
	'model'=>$model,
)); ?>

</div><!-- search-form -->

<div class="shadow border">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'anode-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'lot_num',
		array(
			'name'=>'refnum_search',
			'value'=>'$data->refNum->number',
		),	
		'eap_num',
		array(
			'name'=>'coater_search',
			'value'=>'$data->coater->getFullName()',
		),
		'coat_date',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/manufacturing/viewanode",array("id"=>$data["id"]))',
			'updateButtonUrl'=>'Yii::app()->createUrl("/manufacturing/updateanodelot",array("id"=>$data["id"]))',
		),
	),
	'selectionChanged'=>'anodeSelected',
)); ?>
</div>

<div id="anode-celllist" class="shadow border"></div>

<script type="text/javascript">
	function anodeSelected(target_id){
		var cell_id;
		anode_id = $.fn.yiiGridView.getSelection(target_id);		

		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('anode/ajaxgetanodecells'); ?>',
    		data:
    		{
    			id: anode_id.toString(),
    		},
    		success: function(data){
        		if(data == 'hide')
        		{
        			$('#anode-celllist').hide();
        		}
        		else
        		{
        			$('#anode-celllist').show();
            		$('#anode-celllist').html(data);
        		}
    		},
    	});
	}
</script>

