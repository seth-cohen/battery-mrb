<?php
/* @var $this CyclerController */
/* @var $model Cycler */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Cycler', 'url'=>array('index')),
	array('label'=>'Create Cycler', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cycler-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Cyclers</h1>

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
	'id'=>'cycler-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'sy_number',
		'name',
		'num_channels',
		'cal_date',
		'cal_due_date',
		/*
		'calibrator_id',
		'maccor_job_num',
		'govt_tag_num',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
