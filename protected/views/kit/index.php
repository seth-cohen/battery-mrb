<?php
/* @var $this KitController */
/* @var $model Kit */

$this->breadcrumbs=array(
	'Kits'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create New Kit', 'url'=>array('create')),
	array('label'=>'View All Kits', 'url'=>array('index')),
	array('label'=>'Manage Kit', 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#kit-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>All Kits</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.  
<br/><br/>Multiple lot numbers may be searched for by separating with a space or comma.  It is an 'OR' comparison.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<div class="shadow border">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kit-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		array(
			'name'=>'serial_search',
			'value'=>'$data->getFormattedSerial()'
		),
		array(
			'name'=>'refnum_search',
			'value'=>'$data->refNum->number'
		),
		array(
			'name'=>'anode_search',
			'type'=>'raw',
			'value'=>'$data->getAnodeList()',
		),
		array(
			'name'=>'cathode_search',
			'type'=>'raw',
			'value'=>'$data->getCathodeList()',
		),
		array(
			'name'=>'is_stacked',
			'filter'=>array(''=>'-All-', 0=>'No', 1=>'Yes'),
			'type'=>'raw',
			'value'=>'$data->is_stacked?"Yes":"No"',
		),
		//'kitter_id',
		/*
		'kitting_date',
		'celltype_id',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); ?>
</div>