<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $mfgDataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create New Kit', 'url'=>array('kit/create')),
	array('label'=>'Stack Cells (multi)', 'url'=>array('multistackcells')),
	array('label'=>'Inspect Cells (multi)', 'url'=>array('multiinspectcells')),
	array('label'=>'Laser Weld Cells (multi)', 'url'=>array('multilasercells')),
	array('label'=>'Fill Cells (multi)', 'url'=>array('multifillcells')),
	array('label'=>'Fillport Weld Cells (multi)', 'url'=>array('multitipoffcells')),
	array('label'=>'View All Cells', 'url'=>array('index')),
	array(
		'label'=>'Download Current', 
		'url'=>array('admin'),
		'linkOptions'=>array(
			'id'=>'csv-download',
		),
	),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cell-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Cells</h1>

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

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cell-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'serial_search',
			'value'=>'$data->kit->celltype->name."-".$data->kit->serial_num',
		),
		array(
			'name'=>'refnum_search',
			'value'=>'$data->refNum->number',
		),
		array(
			'name'=>'eap_num',
			'type'=>'raw',
			'value'=>'CHtml::textField("eaps[$data->id]",$data->eap_num)',
		),
		array(
			'name'=>'celltype_search',
			'value'=>'$data->kit->celltype->name',
		),
		array(
			'name'=>'stacker_search',
			'value'=>'$data->stacker->getFullName()',
		),
		array(
			'name'=>'filler_search',
			'value'=>'($data->filler_id==1)?"Not Filled Yet":$data->filler->getFullName()',
		),
		/*
		'stacker_id',
		'stack_date',
		'dry_wt',
		'wet_wt',
		'filler_id',
		'fill_date',
		'inspector_id',
		'inspection_date',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
	//'htmlOptions'=>array('class'=>'shadow grid-view'),
	'selectionChanged'=>'cellSelected',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); ?>
</div>

<hr>

<div id="cell-mfg-details" class="shadow border" style="display:none"></div>

<script type="text/javascript">
	function cellSelected(target_id){
		var cell_id;
		cell_id = $.fn.yiiGridView.getSelection(target_id);		

		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('cell/ajaxmfgupdate'); ?>',
    		data:
    		{
    			id: cell_id.toString(),
    		},
    		success: function(data){
        		if(data == 'hide')
        		{
        			$('#cell-mfg-details').hide();
        		}
        		else
        		{
        			$('#cell-mfg-details').show();
            		$('#cell-mfg-details').html(data);
        		}
    		},
    	});
	}
</script>

<script type="text/javascript">
$(document).ready(function(){

	$('#csv-download').attr('href','');
	
	$('#csv-download').bind('click', function() {	
		var href = '<?php echo $this->createUrl('downloadlist'); ?>';
		href += '?';
		href += $('#cell-search-form :input[name!="r"]').serialize();
		href += '&';
		href +=	$('.filters :input').serialize();
		
		$('#csv-download').attr('href',href);	
	});
});
</script>