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
        	'label'=>'Total Blanking Rejects',
        	'value'=>$model->rejectBlankCount,
        	'visible'=>Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')
        ),
        array(
        	'label'=>'Total Blanking Good',
        	'value'=>$model->goodBlankCount,
        	'visible'=>Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')
        ),
        array(
        	'label'=>'Total Bagging Rejects',
        	'value'=>$model->rejectBagCount,
        	'visible'=>Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')
        ),
        array(
        	'label'=>'Total Bagging Good',
        	'value'=>$model->goodBagCount,
        	'visible'=>Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')
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

<div>
<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
	<?php echo CHtml::button('Show Cell Details', array("id"=>"cell-details-button", "style"=>"float:left;", 'onClick'=>'showCellDetails();')); ?>
	<?php echo CHtml::button('Show Blanking Details', array("id"=>"blanking-button", "style"=>"float:left;", 'onClick'=>'showBlankingDetails();')); ?>
	<?php echo CHtml::button('Show Bagging Details', array("id"=>"bagging-button", "style"=>"float:left;", 'onClick'=>'showBaggingDetails();')); ?>
<?php endif; ?>
</div>
<div class="clear" style="margin-bottom:10px;"></div>

<div class="shadow border clear" id="cell-list">
<?php 
	$this->renderPartial('_ajaxelectrodecells', 
		array(
			'model'=>$model,
			'kitDataProvider'=>$kitDataProvider,
		), 
		false, 
		true
	);
?>
</div>

<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
<div class="shadow border clear"  id='blanking-stats' style="display:none;">
	<?php 
	$this->renderPartial('_ajaxblankingstats', 
		array(
			'model'=>$model,
			'blankingProvider'=>$blankingProvider,
		), 
		false, 
		true
	);
	?>
</div>

<div class="shadow border clear"   id='bagging-stats' style="display:none;">
	<?php 
	$this->renderPartial('_ajaxbaggingstats', 
		array(
			'model'=>$model,
			'baggingProvider'=>$baggingProvider,
		), 
		false, 
		true
	);
	?>
</div>
<?php endif; ?>

<script type="text/javascript">

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