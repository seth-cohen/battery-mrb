<?php
/* @var $this UserController */
/* @var $model User */
/* @var $roleDataProvider CArrayDataProvider */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->getFullName(),
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Details for <?php echo $model->getFullName(); ?></h1>

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
)); 
?>

<?php echo $this->renderPartial('_mfg', array('model'=>$model)); ?>

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

