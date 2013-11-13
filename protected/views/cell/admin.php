<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $mfgDataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Cell', 'url'=>array('index')),
	array('label'=>'Create Cell', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cell-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Cells</h1>

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

<div class="shadow" style="padding:0 5px; margin-bottom:12px; border: 1px solid #888888">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cell-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'serial_search',
			'value'=>'$data->kit->celltype->name."-".$data->kit->serial_num',
		),
		array(
			'name'=>'refnum_search',
			'value'=>'$data->refNum->number',
		),
		'eap_num',
		array(
			'name'=>'celltype_search',
			'value'=>'$data->kit->celltype->name',
		),
		array(
			'name'=>'stacker_search',
			'value'=>'$data->stacker->getFullName()',
		),
		/*
		'stacker_id',
		'stack_date',
		'dry_wt',
		'wet_wt',
		'filler_id',
		'fill_date',
		'inspector_id',
		'inspection_date',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
	//'htmlOptions'=>array('class'=>'shadow grid-view'),
	'selectionChanged'=>'cellSelected',
)); ?>
</div>

<hr>

<div id="cell-mfg-details" class="shadow" style="border:1px solid #888888; padding:5px;"></div>

<script type="text/javascript">
	function cellSelected(target_id){
		var cell_id;
		
		cell_id = $.fn.yiiGridView.getSelection(target_id);

		
		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('cell/ajaxmfgupdate'); ?>',
    		data:
    		{
    			id: cell_id.toString(),
    		},
    		success: function(data){
				$('#cell-mfg-details').html(data);
    		},
    	});
	}
</script>
