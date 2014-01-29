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

<h1>All Channels</h1>
<p>
*Channels chan be marked out of commission using the appropriate drop down list.
</p>
<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<div class="shadow border">
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
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("Channel_Status[$data->id]", $data->in_commission, array("0"=>"No", "1"=>"Yes"), array(
						"class"=>"status-dropdown",
						"onChange"=>"statusSelected(this)",
						"style"=>"width:100px",
						"data-id"=>$data->id,
			))',
			'filter'=>Channel::forListBoolean(),
		),
		'min_voltage',
		'max_voltage',
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

<script type="text/javascript">
function statusSelected(sel)
{
	var status = $('option:selected', $(sel)).attr("value");
	var id = $(sel).data("id");
	
	$.ajax({
		url: '<?php echo $this->createUrl('/channel/ajaxsetstatus'); ?>',
		type: 'POST',
		data: 
		{
			id: id,
			status: status,
		},
		success: function(data) {
			if(data == '1'){
				$(sel).css('border', '2px solid green');
				setTimeout(function() {
					$(sel).css('border', '1px solid');
				}, 2000);
			}else{
				$(sel).css('border', '2px solid red');
				setTimeout(function() {
					$(sel).css('border', '1px solid');
				}, 2000);
			}
		},
	});	
}
</script>
