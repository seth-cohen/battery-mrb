<?php
/* @var $this ManufacturingController */
/* @var $model Electrode */
/* @var $kitDataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Electrodes'=>array('index'),
    'Lot '.$model->lot_num,
);

$this->menu=array(
	array('label'=>'Edit This Lot', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
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
        'moisture',
    ),
    'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>

<div class="shadow border">
<h2 style="width:100%; text-align:center">Cells using <?php echo $model->is_anode?'Anode':'Cathode'; ?> Lot <?php echo $model->lot_num; ?> </h2>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kit-grid',
	'dataProvider'=>$kitDataProvider,
	'columns'=>array(
		array(
			'name'=>'No.',
			'value'=>'$data["num"]',
		),
		array(
			'name'=>'Cell Serial',
			'type'=>'html',
			'value'=>'CHtml::link(CHtml::encode($data["kit"]), array("cell/view", "id"=>$data["id"]))',
		),
		array(
			'name'=>'Stack Date',
			'value'=>'$data["stack_date"]',
		),
		array(
			'name'=>'Cell Location',
			'value'=>'$data["location"]',
		),
	),
	'emptyText'=>'Oops, no cells using this lot yet',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>
</div>
