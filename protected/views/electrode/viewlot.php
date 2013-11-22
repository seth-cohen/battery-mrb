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
    array('label'=>'Create Electrode', 'url'=>array('create')),
    array('label'=>'Edit Electrode Lot', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Viw All Electrodes', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin')),
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
        'eap_num',
        array(
        	'label'=>'Coater',
        	'value'=>$model->coater->getFullName(),
        ),
        'coat_date',
        array(
        	'label'=>'Reference No.',
        	'value'=>$model->refNum->number,
        ),
    ),
    'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>

<div class="shadow border">
<h2>Kits using <?php echo $model->is_anode?'Anode':'Cathode'; ?> Lot <?= $model->lot_num; ?> </h2>
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
			'value'=>'$data["kit"]',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'viewButtonUrl'=>'Yii::app()->createUrl("/kit/view",array("id"=>$data["id"]))',
		),
	),
	'emptyText'=>'Oops, no cells built yet',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>
</div>
