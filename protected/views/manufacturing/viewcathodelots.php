<?php
/* @var $this ManufacturingController */
/* @var $model Cathode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cathodes'=>array('viewcathodelots'),
	'View Lots',
);

$this->menu=array(
	array('label'=>'Create Anode Lot', 'url'=>array('createanodelot')),
	array('label'=>'Create Cathode Lot', 'url'=>array('createcathodelot')),
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

<h1>View Cathode Lots</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_cathodesearch',array(
	'model'=>$model,
)); ?>

</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cathode-grid',
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
			'viewButtonUrl'=>'Yii::app()->createUrl("/manufacturing/viewcathode",array("id"=>$data["id"]))',
			'updateButtonUrl'=>'Yii::app()->createUrl("/manufacturing/updatecathodelot",array("id"=>$data["id"]))',
		),
	),
)); ?>
