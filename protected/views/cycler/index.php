<?php
/* @var $this CyclerController */
/* @var $model Cycler */

$this->breadcrumbs=array(
	'Testlab'=>array('/testlab'),
	'Cyclers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Add New Cycler', 'url'=>array('create')),
	array('label'=>'Cycler Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
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

<div class="shadow border">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cycler-grid',
	'dataProvider'=>$model->notGeneric()->search(),
	'filter'=>$model,
	'columns'=>array(
		'sy_number',
		'name',
		'num_channels',
		'cal_due_date',
		array(
			'name' => 'calibrator_search',
			'value' => '$data->calibrator->getFullName()',
		),
		/*
		'maccor_job_num',
		*/
		'govt_tag_num',
		array(
			'class'=>'CButtonColumn',
			'template' => '{view} {update}',
		),
	),
	'selectionChanged'=>'cyclerSelected',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); ?>
</div>

<div id="test-details" class="shadow border" style="display:none"></div>

<script type="text/javascript">
	function cyclerSelected(target_id){
		var cycler_id = $.fn.yiiGridView.getSelection(target_id);		

		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('cycler/ajaxcyclertests'); ?>',
    		data:
    		{
    			id: cycler_id.toString(),
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

<script type="text/javascript">
$(document).ready(function(){

	$('body').on('click', '.page, .previous', function(){
		//alert('test');
		
	});
});
</script>
