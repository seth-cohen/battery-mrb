<?php
/* @var $this NcrController */
/* @var $model Ncr */

$this->breadcrumbs=array(
	'NCRS'=>array('index'),
	'View All',
);

$this->menu=array(
	array('label'=>'Put Cells on NCR', 'url'=>array('putcellsonncr')),
	array('label'=>'Dispo Cells on NCR', 'url'=>array('dispositioncells')),
	array('label'=>'NCR Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ncr-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>View All NCRs</h1>

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
	'id'=>'ncr-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'number',
		'date',
		array(
			'header'=>'No. of Cells',
			'value'=>'count($data->cells)',
		),
		array(
			'header'=>'No. Open Cells',
			'value'=>'count($data->openCells)',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
		),
	),
	'selectionChanged'=>'ncrSelected',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); ?>
</div>

<div id="ncr-details" class="shadow border" style="display:none"></div>

<script type="text/javascript">
	function ncrSelected(target_id){
		var battery_id;
		battery_id = $.fn.yiiGridView.getSelection(target_id);		

		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('ncr/ajaxcellsforncr'); ?>',
    		data:
    		{
    			id: battery_id.toString(),
    		},
    		success: function(data){
        		if(data == 'hide')
        		{
        			$('#ncr-details').hide();
        		}
        		else
        		{
        			$('#ncr-details').show();
            		$('#ncr-details').html(data);
        		}
    		},
    	});
	}
</script>