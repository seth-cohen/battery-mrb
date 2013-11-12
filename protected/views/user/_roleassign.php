
	<div id="role-checkboxlist">
        <?php echo CHtml::activeLabel($model, 'roles'); ?>
        <?php echo CHtml::activecheckBoxList($model, 'roleIds',
            CHtml::listData(Role::model()->findAll(array('order'=>'name')), 'id', 'name'),
            array(
            	    'checkAll' => 'Check All',
            	    'separator'=>'',
            	    'template'=> '<div>{input} {label}</div>',
            		'class'=>'role-checkbox',
            	)
            ); ?>
        <?php echo CHtml::error($model, 'roles'); ?>
    </div>
    