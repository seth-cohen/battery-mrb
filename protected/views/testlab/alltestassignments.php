<?php
/* @var $this TestLabController */
/* @var $model TestAssignment */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'All Test Assignments',
);

$this->menu=array(
	array('label'=>'Put cells on Formation', 'url'=>array('cellformation')),
	array('label'=>'View Cells on Formation', 'url'=>array('formationindex')),
	array('label'=>'Put cells on CAT', 'url'=>array('cellcat')),
	array('label'=>'View Cells on CAT', 'url'=>array('catindex')),
	array('label'=>'Condition for Assembly', 'url'=>array('cellconditioning')),
	array('label'=>'View Cells Conditioning', 'url'=>array('conditioningindex')),
	array('label'=>'Test Reassignments', 'url'=>array('testreassignment')),
	array('label'=>'Move Cells to Storage', 'url'=>array('storage')),
	array('label'=>'Deliver Cells to Assembly', 'url'=>array('deliverforbattery')),
	array('label'=>'View All Cells', 'url'=>array('/cell/index')),
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

<h1>Viewing All Test Assignments</h1>

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
			'value'=>'$data->cell->kit->getFormattedSerial()',
		),
		array(
			'name'=>'is_active',
			'type'=>'boolean',
			'filter'=>array(0=>'No', 1=>'Yes'),
		),
		array(
			'name'=>'type_search',
			'value'=>function($data, $row)
			{
				if($data->is_formation == 1){
					return 'Form';
				}elseif($data->is_conditioning ==1){
					return 'COND';
				}else{
					return 'CAT';
				}
			},
			'filter' =>array(0=>'FORM', 1=>'CAT', 2=>'COND'),
		),
		array(
			'name'=>'chamber_search',
			'value'=>'$data->chamber->name',
			'cssClassExpression'=>function($row, $data){
				if($data->chamber->in_commission == 0){
					return 'red-text';
				}
			}
		),
		array(
			'name'=>'cycler_search',
			'value'=>'$data->channel->cycler->name." {".$data->channel->number."}"',
		),
		'test_start',
		array(
			'name' => 'test_start_time',
			'value' => 'date("H:i:s", $data->test_start_time)',
			'filter'=>false,
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
			'viewButtonUrl'=>'Yii::app()->createUrl("cell/view", array("id"=>$data->cell->id))',
			'updateButtonUrl'=>'Yii::app()->createUrl("cell/update", array("id"=>$data->cell->id))',
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
    			test:'1',
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
