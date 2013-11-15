<?php
/* @var $this ManufacturingController */
/* @var $model Electrode */

$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Electrodes'=>array('index'),
    'Lot '.$model->lot_num,
);

$this->menu=array(
    array('label'=>'Electrodes List', 'url'=>array('index')),
    array('label'=>'Create Electrode', 'url'=>array('createlot')),
    array('label'=>'Edit Electrode Lot', 'url'=>array('updatelot', 'id'=>$model->id)),
    array('label'=>'Delete Electrode', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Electrode Admin', 'url'=>array('admin')),
);
?>

<h1>Electrode Lot <?php echo $model->lot_num; ?> Details</h1>

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
)); ?>

<?php foreach($model->kits as $kit): ?>
	<?php echo $kit->getFormattedSerial(); ?>
<?php endforeach; ?>
