<?php
/* @var $this UserController */
/* @var $model User */
/* @var $roleDataProvider CArrayDataProvider */
/* @var $cellDataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->getFullName(),
);


$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Edit User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'View All Users', 'url'=>array('index')),
	array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>Details for "<?php echo $model->getFullName(); ?>"</h1>

<div class="shadow border">
<?php 
	/* make sure this isn't the last channel for the cycler */
		echo CHtml::link('Next User', 
			array(
				'user/view',
				'id'=>$model->id+1,
			),
			array(
				'style'=>'float:right; margin-left:25px; margin-right:25px;',
			)
		);
?>
<?php 
	/* make sure this isn't the first channel for the cycler */
	if ($model->id != 1)
	{
		echo CHtml::link('Previous User', 
			array(
				'user/view',
				'id'=>$model->id-1,
			),
			array(
				'style'=>'float:right;',
			)
		);
	}?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		//'password',
		'first_name',
		'last_name',
		'email',
		'depart_id',
	),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
)); ?>
</div>

<hr>
<?php /* If the user is an admin add the assign roles button */
	echo CHtml::button('Assign Roles', 
		array(
			'id'=>'btnAssign',
		));
?>

<div id="role-wrapper" style="display:none">
<?php echo $this->renderPartial('_roleassign', array('model'=>$model)); ?>
</div>

<div class="shadow border">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'role-grid',
	'dataProvider'=>$roleDataProvider,
	'columns'=>array(
		array(
			'name'=>'No.',
			'value'=>'$data["id"]',
		),
		array(
			'name'=>'Assigned Roles',
			'value'=>'$data["role"]',
		),
		array(
			'class'=>'CCheckBoxColumn',
			'selectableRows'=>2,
		),
	),
	'emptyText'=>'Oops, no roles assigned yet',
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager' => array(
		'cssFile' => false,
	)
)); 
?>
</div>

<?php echo $this->renderPartial('_mfg', array('cellDataProvider'=>$cellDataProvider)); ?>

<?php Yii::app()->clientScript->registerScript('testscript',"
    $('#btnAssign').bind('click',function(){
    	$('#role-wrapper').toggle();
    });
    
    $('.role-checkbox').bind('change',function(){
    	var roles = [];
    	$('.role-checkbox').each(function(){
    		if (this.checked){
    			roles.push(this.value);
    		}
    	});
    	$.ajax({
    		url: '".$this->createUrl('user/ajaxassignrole')."',
    		data: 
    		{
    			roles: roles,
    			id: ".$model->id.",
    		},
    		type: 'POST',
    		complete: 
	    		function() {
	    			$.fn.yiiGridView.update('role-grid');
	            },
    	});
    });
",CClientScript::POS_READY);?>

