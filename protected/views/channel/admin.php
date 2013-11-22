<?php
/* @var $this ChannelController */
/* @var $model Channel */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Channels'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Channel', 'url'=>array('index')),
	array('label'=>'Create Channel', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#channel-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Channels</h1>

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
	'id'=>'channel-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'number',
		array(
			'name'=>'cycler_search',
			'value'=>'$data->cycler->name',
		),
		'max_charge_rate',
		'max_discharge_rate',
		'multirange',
		/*
		'in_use',
		'in_commission',
		'min_voltage',
		'max_voltage',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); ?>
</div>
