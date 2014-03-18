<?php
/* @var $this ManufacturingController */
/* @var $model Electrode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Electrodes'=>array('index'),
	'View Lots',
);

$this->menu=array(
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
    array('label'=>'Electrode Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#anode-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>All Electrode Lots</h1>

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
	'id'=>'electrode-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'lot_num',
		array(
			'name'=>'refnum_search',
			'value'=>'$data->refNum->number',
		),	
		'eap_num',
		array(
			'name'=>'is_anode',
			'value'=>'$data->is_anode?"Anode":"Cathode"',
			'filter'=>array('0'=>'Cathode', '1'=>'Anode'),
			'htmlOptions'=>array('width'=>'60'),
		),
		array(
			'name'=>'coater_search',
			'value'=>'$data->coater->getFullName()',
		),
		'coat_date',
		'thickness',
		'moisture',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view} {update}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/electrode/view",array("id"=>$data["id"]))',
			'updateButtonUrl'=>'Yii::app()->createUrl("/electrode/update",array("id"=>$data["id"]))',
		),
	),
	'selectionChanged'=>'electrodeSelected',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); ?>
</div>

<div id="cells-list" class="shadow border" style="display:none"></div>

<script type="text/javascript">
	function electrodeSelected(target_id){
		var electrode_id;
		electrode_id = $.fn.yiiGridView.getSelection(target_id);		

		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('electrode/ajaxgetelectrodecells'); ?>',
    		data:
    		{
    			id: electrode_id.toString(),
    		},
    		success: function(data){
        		if(data == 'hide')
        		{
        			$('#cells-list').hide();
        		}
        		else
        		{
        			$('#cells-list').show();
            		$('#cells-list').html(data);
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
