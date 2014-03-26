<?php
/* @var $this ManufacturingController */
/* @var $model Electrode */
/* @var $kitDataProvider CArrayDataProvider */
/* @var $baggingProvider CActiveDataProvider */
/* @var $blankingProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Electrodes'=>array('index'),
    'Lot '.$model->lot_num,
);

$this->menu=array(
	array('label'=>'Edit This Lot', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
    array('label'=>'Calendar Electrode Lot', 'url'=>array('calendarlot')),
    array('label'=>'Blank Electrode Lot', 'url'=>array('blanklot')),
    array('label'=>'Bag Cathode Lot', 'url'=>array('baglot')),
    array('label'=>'View All Electrodes', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);
?>

<h1>Electrode Lot <?php echo $model->lot_num; ?> Details</h1>

<div class="shadow border">
<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'lot_num',
		array(
			'label'=>'Anode/Cathode',
			'value'=>($model->is_anode)?'Anode':'Cathode',
		),
		array(
        	'label'=>'Reference No.',
        	'value'=>$model->refNum->number,
        ),
        'eap_num',
        array(
        	'label'=>'Coater',
        	'value'=>$model->coater->getFullName(),
        ),
        'coat_date',
        'thickness',
        'cal_date',
        array(
        	'label'=>'Total Bagging Rejects',
        	'value'=>$model->rejectBagCount,
        ),
        array(
        	'label'=>'Total Bagging Good',
        	'value'=>$model->goodBagCount,
        ),
        array(
        	'label'=>'Total Blanking Rejects',
        	'value'=>$model->rejectBlankCount,
        ),
        array(
        	'label'=>'Total Blanking Good',
        	'value'=>$model->goodBlankCount,
        ),
        array(
        	'label'=>'Cal Operator',
        	'value'=>($model->calendar==null)?'N/A':$model->calendar->getFullName(),
        ),
        'moisture',
    ),
    'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>


<?php 
$this->widget('zii.widgets.jui.CJuiTabs',array(
    'tabs'=>array(
        'Cell Details'=>array('content'=>'<div id="cells-list"></div>',
			'id'=>'tab1',
		),
        'Blanking Details'=>array('content'=>
        	$this->renderPartial('_ajaxblankingstats', 
				array(
					'model'=>$model,
					'blankingProvider'=>$blankingProvider,
				), 
				true, 
				true
			),
			'id'=>'tab2',
		),
        // panel 3 contains the content rendered by a partial view
        'Bagging Details'=>array('content'=>
        	$this->renderPartial('_ajaxbaggingstats', 
				array(
					'model'=>$model,
					'baggingProvider'=>$baggingProvider,
				), 
				true, 
				true
			),
			'id'=>'tab3',
		),
    ),
    // additional javascript options for the tabs plugin
    'options'=>array(
        'collapsible'=>true,
    ),
));
?>

<script type="text/javascript">
$(function(){
	$.ajax({
		type:'get',
		url: '<?php echo $this->createUrl('electrode/ajaxgetelectrodecells'); ?>',
		data:
		{
			id: '<?php echo $model->id; ?>',
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
});

</script>







