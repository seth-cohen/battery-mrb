<?php
/* @var $this CellController */
/* @var $model Cell */

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cell-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'serial_num',
			'value'=>'$data->celltype->name."-".$data->serial_num',
		),
		array(
			'name'=>'kit_search',
			'value'=>'"Lot# ".$data->kit->lot_num',
		),
		'ref_num',
		'eap_num',
		array(
			'name'=>'celltype_search',
			'value'=>'$data->celltype->name',
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
)); ?>
