
	<div id="column-checkboxlist">
        <?php echo CHtml::checkBoxList('Columns[]', $visibleColumns,
            CHtml::listData(Cell::getColumnList(),'id', 'value'),
            array(
            	    'separator'=>'',
            	    'template'=> '<div>{input} {label}</div>',
            		'class'=>'column-checkbox',
            	)
            ); ?>
    </div>