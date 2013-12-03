
	<div id="column-checkboxlist">
        <?php echo CHtml::checkBoxList('Printcolumns[]', $printColumns,
            CHtml::listData(Cell::getColumnList(),'id', 'value'),
            array(
            	    'separator'=>'',
            	    'template'=> '<div>{input} {label}</div>',
            		'class'=>'column-checkbox',
            	)
            ); ?>
    </div>