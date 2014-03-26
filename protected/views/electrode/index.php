<?php
/* @var $this ElectrodeController */
/* @var $model Electrode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
	'Electrodes'=>array('index'),
	'View Lots',
);

$this->menu=array(
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
    array('label'=>'Calendar Electrode Lot', 'url'=>array('calendarlot')),
    array('label'=>'Blank Electrode Lot', 'url'=>array('blanklot')),
    array('label'=>'Bag Cathode Lot', 'url'=>array('baglot')),
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
	'dataProvider'=>$model->notGeneric()->search(),
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

 <div id="details-wrapper"  style="display:none">
	<div>
<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
		<?php echo CHtml::button('Show Cell Details', array("id"=>"cell-details-button", "style"=>"float:left;", 'onClick'=>'showCellDetails();')); ?>
		<?php echo CHtml::button('Show Blanking Details', array("id"=>"blanking-button", "style"=>"float:left;", 'onClick'=>'showBlankingDetails();')); ?>
		<?php echo CHtml::button('Show Bagging Details', array("id"=>"bagging-button", "style"=>"float:left;", 'onClick'=>'showBaggingDetails();')); ?>
<?php endif; ?>
	</div>
	<div class="clear" style="margin-bottom:10px;"></div>
	<div id="cell-list" class="shadow border" style="display:none"></div>
	<div id="blanking-stats" class="shadow border" style="display:none"></div>
	<div id="bagging-stats" class="shadow border" style="display:none"></div>
</div>

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
        			$('#cell-list').hide();
        			$('#details-wrapper').hide();
        		}
        		else
        		{
        			$('#cell-list').show();
            		$('#cell-list').html(data);
            		$('#details-wrapper').show();
        		}
    		},
    	});
<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('electrode/ajaxgetblankingstats'); ?>',
    		data:
    		{
    			id: electrode_id.toString(),
    		},
    		success: function(data){
        		if(data == 'hide')
        		{
        			$('#blanking-list').hide();
        		}
        		else
        		{
            		$('#blanking-stats').html(data);
        		}
    		},
    	});

		$.ajax({
			type:'get',
    		url: '<?php echo $this->createUrl('electrode/ajaxgetbaggingstats'); ?>',
    		data:
    		{
    			id: electrode_id.toString(),
    		},
    		success: function(data){
        		if(data == 'hide')
        		{
        			$('#bagging-stats').hide();
        		}
        		else
        		{
            		$('#bagging-stats').html(data);
        		}
    		},
    	});
<?php endif;?>

	}
</script>

<script type="text/javascript">
$(document).ready(function(){

	$('body').on('click', '.page, .previous', function(){
		//alert('test');
		
	});
});

function showCellDetails(){
	//unhide the spares selection wrapper
	$('#cell-list').show();
	$('#blanking-stats').hide();
	$('#bagging-stats').hide();
}

<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
function showBlankingDetails(){
	//unhide the spares selection wrapper
	$('#cell-list').hide();
	$('#blanking-stats').show();
	$('#bagging-stats').hide();
}

function showBaggingDetails(){
	//unhide the spares selection wrapper
	$('#cell-list').hide();
	$('#blanking-stats').hide();
	$('#bagging-stats').show();
}
<?php endif; ?>
</script>


<?php 
/*
 




 */
?>
