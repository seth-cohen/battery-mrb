<?php
/* @var $this ChamberController */
/* @var $model Chamber */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Chambers'=>array('index'),
	'Index',
);

$this->menu=array(
	array('label'=>'Add New Chamber', 'url'=>array('create')),
	array('label'=>'Manage Chambers', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
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

<h1>Viewing All Chambers</h1>

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
	'dataProvider'=>$model->notGeneric()->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'brand',
		'model',
		'serial_num',
		'govt_tag_num',
		array(
			'name'=>'in_commission',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("Chamber_Status[$data->id]", $data->in_commission, array("0"=>"No", "1"=>"Yes"), array(
						"class"=>"status-dropdown",
						"onChange"=>"statusSelected(this)",
						"style"=>"width:100px",
						"data-id"=>$data->id,
			))',
			'filter'=>Channel::forListBoolean(),
		),
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
	'selectionChanged'=>'chamberSelected',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	),
)); ?>
</div>

<div id="test-details" class="shadow border" style="display:none"></div>

<script type="text/javascript">
function statusSelected(sel)
{
	var status = $('option:selected', $(sel)).attr("value");
	var id = $(sel).data("id");
	
	$.ajax({
		url: '<?php echo $this->createUrl('/chamber/ajaxsetstatus'); ?>',
		type: 'POST',
		data: 
		{
			id: id,
			status: status,
		},
		success: function(data) {
			var message;
			if(data == '1'){
				message = $("<br/><span style='color:green'>Change Successful</span>");
				$(sel).css('border', '2px solid green');
				$(sel).parent().append(message);
				setTimeout(function() {
					$(sel).css('border', '1px solid');
					message.remove();
				}, 2000);
			}else{
				message = $("<br/><span style='color:red'>Change Failed</span>");
				$(sel).css('border', '2px solid red');
				$(sel).parent().append(message);
				setTimeout(function() {
					$(sel).css('border', '1px solid');
					message.remove();
				}, 2000);
			}
		},
	});	
}
</script>

<script type="text/javascript">
	function chamberSelected(target_id){
		var chamber_id;
		chamber_id = $.fn.yiiGridView.getSelection(target_id);		

		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('chamber/ajaxchambertests'); ?>',
    		data:
    		{
    			id: chamber_id.toString(),
    		},
    		success: function(data){
        		if(data == 'hide')
        		{
        			$('#test-details').hide();
        		}
        		else
        		{
        			$('#test-details').show();
            		$('#test-details').html(data);
        		}
    		},
    	});
	}
</script>