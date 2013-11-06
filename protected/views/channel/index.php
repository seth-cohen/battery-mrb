<?php
/* @var $this ChannelController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Channels',
);

$this->menu=array(
	array('label'=>'Create Channel', 'url'=>array('create')),
	array('label'=>'Manage Channel', 'url'=>array('admin')),
);
?>

<h1>Channels</h1>
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'channel-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'number',
			'htmlOptions'=>array('style'=>'width:50px;'),
		),
		array(
			'name'=>'cycler_search',
			'value'=>'$data->cycler->name',
			'filter'=>Cycler::forList(),
		),
		'max_charge_rate',
		'max_discharge_rate',
		array(
			'name'=>'multirange',
			'value'=>'($data->multirange==0) ? \'No\':\'Yes\'',
			'filter'=>Channel::forListBoolean(),
		),
		array(
			'name'=>'in_use',
			'value'=>'($data->in_use==0) ? \'No\':\'Yes\'',
			'filter'=>Channel::forListBoolean(),
			'htmlOptions'=>array('style'=>'width:50px;'),
		),
		array(
			'name'=>'in_commission',
			'value'=>'($data->in_commission==0) ? \'No\':\'Yes\'',
			'filter'=>Channel::forListBoolean(),
		),
		'min_voltage',
		'max_voltage',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
