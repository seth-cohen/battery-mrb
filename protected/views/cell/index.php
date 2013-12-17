<?php
/* @var $this CellController */
/* @var $model Cell */
/* @var $visibleColumns array() */
/* @var $mfgDataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Cells'=>array('index'),
	'View All Cells',
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

<h1>View All Cells</h1>

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

<div style="float:right;">
<?php echo CHtml::button('Column Selector', 
			array(
				'title'=>'Show Column Selectors',
				'onClick'=>'js:toggleColumns();',
				'style'=>'clear:both;',
			)
); ?>
</div><!-- column button selector -->
<div style="clear:both;"></div>

<div class="column-wrapper border" style="padding:5px; display:none;">
<em><b>Visible Columns</b> (only 10 can be displayed)</em>
<?php  echo $this->renderPartial('_columnvisibility', array('visibleColumns'=>$visibleColumns)); ?>
</div>

<div class="column-wrapper border" style="padding:5px; display:none">
<em><b>CSV Columns</b> (all columns can be saved to CSV)</em>
<?php  echo $this->renderPartial('_columnprinting', array('printColumns'=>$visibleColumns)); ?>
</div>

<div class="shadow border" >
<?php $this->widget(/*'zii.widgets.grid.CGridView'*/'application.extensions.EExcelView', array(
	'id'=>'cell-grid',
	'dataProvider'=>$model->search(),
	'disablePaging'=>false,
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'serial_search',
			'value'=>'$data->kit->celltype->name."-".$data->kit->serial_num',
			'visible'=>in_array(1,$visibleColumns),
		),
		array(
			'name'=>'refnum_search',
			'value'=>'$data->refNum->number',
			'visible'=>in_array(2,$visibleColumns),
		),
		array(
			'name'=>'eap_num',
			'visible'=>in_array(3,$visibleColumns),
		),
		array(
			'name'=>'celltype_search',
			'value'=>'$data->kit->celltype->name',
			'visible'=>in_array(4,$visibleColumns),
		),
		array(
			'name'=>'stacker_search',
			'value'=>'$data->stacker->getFullName()',
			'visible'=>in_array(5,$visibleColumns),
		),
		array(
			'name'=>'stack_date',
			'visible'=>in_array(6,$visibleColumns),
		),
		array(
			'name'=>'inspector_search',
			'value'=>'$data->inspector->getFullName()',
			'visible'=>in_array(7,$visibleColumns),
		),
		array(
			'name'=>'inspection_date',
			'visible'=>in_array(8,$visibleColumns),
		),
		array(
			'name'=>'laserwelder_search',
			'value'=>'$data->laserwelder->getFullName()',
			'visible'=>in_array(9,$visibleColumns),
		),
		array(
			'name'=>'laserweld_date',
			'visible'=>in_array(10,$visibleColumns),
		),
		array(
			'name'=>'filler_search',
			'value'=>'$data->filler->getFullName()',
			'visible'=>in_array(11,$visibleColumns),
		),
		array(
			'name'=>'fill_date',
			'visible'=>in_array(12,$visibleColumns),
		),
		array(
			'name'=>'portwelder_search',
			'value'=>'$data->portwelder->getFullName()',
			'visible'=>in_array(13,$visibleColumns),
		),
		array(
			'name'=>'portweld_date',
			'visible'=>in_array(14,$visibleColumns),
		),
		array(
			'name'=>'dry_wt',
			'visible'=>in_array(15,$visibleColumns),
		),
		array(
			'name'=>'wet_wt',
			'visible'=>in_array(16,$visibleColumns),
		),
		array(
			'name'=>'anode_search',
			'value'=>'$data->kit->getAnodeList()',
			'visible'=>in_array(17,$visibleColumns),
		),
		array(
			'name'=>'cathode_search',
			'value'=>'$data->kit->getCathodeList()',
			'visible'=>in_array(18,$visibleColumns),
		),
		'location',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
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
	
	$('#csv-download').on('click', function() {	
		var href = '<?php echo $this->createUrl('downloadlist'); ?>';
		href += '?';
		href += $('#cell-search-form :input[name!="r"]').serialize();
		href += '&';
		href +=	$('.filters :input').serialize();
		
		$('#csv-download').attr('href',href);	
	});

	$('#column-checkboxlist').on('change', 'input[name="Columns[]"]', function(event){
		/* limit to 10 columns visible */
		var bMaxColumns = $('input[name="Columns[]"]:checked').length >= 10;
		$('input[name="Columns[]"]').not(':checked').attr('disabled', bMaxColumns);

		/* all visible columns shouls be printed to CSV also */
		$('input[name="Columns[]"]:checked').each(function(){
			$('input[name="Printcolumns[]"][value='+this.value+']').prop('checked',true);
		});
		$('input[name="Columns[]"]').not(':checked').each(function(){
			$('input[name="Printcolumns[]"][value='+this.value+']').prop('checked',false);
		});
		
		var data = $('input[name="Columns[]"]:checked').serialize();
		$.fn.yiiGridView.update('cell-grid', {
			data: data,
	    });
	});
});

function toggleColumns() {
    $('.column-wrapper').toggle();
}

</script>