<?php
/* @var $this ChamberController */
/* @var $model Chamber */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Chambers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Add New Chamber', 'url'=>array('create')),
	array('label'=>'Manage Chambers', 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#chamber-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Chambers</h1>

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

<div class="shadow border">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'chamber-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'brand',
		'model',
		'serial_num',
		'in_commission',
		/*
		'govt_tag_num',
		'cycler_id',
		'min_temp',
		'max_temp',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}'
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	),
)); ?>
</div>