<?php
/* @var $this ElectrodeController */
/* @var $model Electrode */
/* @var $blankingProvider CActiveDataProvider */
/* @var $baggingProvider CActiveDataProvider */


$this->breadcrumbs=array(
	'Manufacturing'=>array('/manufacturing'),
    'Electrodes'=>array('index'),
    'Lot '.$model->lot_num,
	'Edit',
);

$this->menu=array(
	array('label'=>'View This Lot', 'url'=>array('view', 'id'=>$model->id)),
    array('label'=>'Create Electrode Lot', 'url'=>array('create')),
    array('label'=>'Calendar Electrode Lot', 'url'=>array('calendarlot')),
    array('label'=>'Blank Electrode Lot', 'url'=>array('blanklot')),
    array('label'=>'Bag Cathode Lot', 'url'=>array('baglot')),
    array('label'=>'View All Electrodes', 'url'=>array('index')),
    array('label'=>'Electrode Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

$controller = $this;
Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
?>

<h1>Update Electrode Lot <?php echo $model->lot_num; ?></h1>

<?php $this->renderPartial('_updateform', array('model'=>$model)); ?>

<?php if(Yii::app()->user->checkAccess('manufacturing supervisor') || Yii::app()->user->checkAccess('manufacturing engineer')): ?>
<div>
	<?php echo CHtml::button('Edit Blanking Details', array("id"=>"blanking-button", "style"=>"float:left;", 'onClick'=>'showBlankingDetails();')); ?>
	<?php echo CHtml::button('Edit Bagging Details', array("id"=>"bagging-button", "style"=>"float:left;", 'onClick'=>'showBaggingDetails();')); ?>
</div>
<div class="clear" style="margin-bottom:10px;"></div>

<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'blanking-dialog',
	'options'=>array(
		'title'=>'Edit Blanking Details for '.$model->lot_num, 
		'autoOpen' => false,
		'width'=>500,
		'modal'=>true,
		'buttons'=>array(
			'Submit'=>'js:function(){submitEditBlanking();}',
			'Cancel'=>'js:function(){$( this ).dialog( "close" );}',
		),
	),
));?>

	<?php $blankingForm=$this->beginWidget('CActiveForm', array(
		'id'=>'blanking-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>true,
	)); ?>
	
	<div class="shadow border" id="blanking-wrapper" style="margin:auto;display:none"> 
	<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>"blanking-grid",
			'template'=>'{items}',
			'dataProvider'=>$blankingProvider,
			'columns'=>array(
				array(
		            'id'=>'autoId',
		            'class'=>'CCheckBoxColumn',
		            'selectableRows' => '50',   
		        ),
				array(
					'name'=>'Blanking Date',
					'type'=>'raw',
		        	'value'=>function($data,$row){
						return CHtml::textField('dates['.$data->id.']',$data->blanking_date,array(
							'style'=>'width:100px;', 
							'class'=>'hasDatePicker',
						));
		        	},
				),
				array(
					'name'=>'Reject Count',
					'type'=>'raw',
		        	'value'=>function($data,$row){
						return CHtml::textField('reject_counts['.$data->id.']',$data->reject_count,array(
							'style'=>'width:50px;', 
						));
		        	},
				),
				array(
					'name'=>'Good Count',
					'type'=>'raw',
		        	'value'=>function($data,$row){
						return CHtml::textField('good_counts['.$data->id.']',$data->good_count,array(
							'style'=>'width:50px;', 
						));
		        	},
				),
				array(
					'header' => 'Blanker',
					'type' => 'raw',
					'value' => function($data, $row){					
						$returnString = CHtml::textField('user_names['.$data->id.']',User::getFullNameProper($data->blanker_id),array(
							"style"=>"width:110px;",
							"class"=>"autocomplete-user-input",
							"autocomplete"=>"off",
						));
							
						$returnString.= CHtml::hiddenField('user_ids['.$data->id.']',$data->blanker_id, array("class"=>"user-id-input"));
					
						return $returnString;
					},
				),
			),
			'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
			'pager' => array(
				'cssFile' => false,
			)
		)); 
		?>
	</div>

	<?php $this->endWidget(); ?> <!--  EDIT BLANKING DETAILS FORM -->
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	'id'=>'bagging-dialog',
	'options'=>array(
		'title'=>'Edit Bagging Details for '.$model->lot_num, 
		'autoOpen' => false,
		'width'=>500,
		'modal'=>true,
		'buttons'=>array(
			'Submit'=>'js:function(){submitEditBagging();}',
			'Cancel'=>'js:function(){$( this ).dialog( "close" );}',
		),
	),
));?>

	<?php $blankingForm=$this->beginWidget('CActiveForm', array(
		'id'=>'bagging-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>true,
	)); ?>
	
	<div class="shadow border" id="bagging-wrapper" style="margin:auto;display:none"> 
	<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>"bagging-grid",
			'template'=>'{items}',
			'dataProvider'=>$baggingProvider,
			'columns'=>array(
				array(
		            'id'=>'autoId',
		            'class'=>'CCheckBoxColumn',
		            'selectableRows' => '50',   
		        ),
				array(
					'name'=>'Bagging Date',
					'type'=>'raw',
		        	'value'=>function($data,$row){
						return CHtml::textField('dates['.$data->id.']',$data->bagging_date,array(
							'style'=>'width:100px;', 
							'class'=>'hasDatePicker',
						));
		        	},
				),
				array(
					'name'=>'Reject Count',
					'type'=>'raw',
		        	'value'=>function($data,$row){
						return CHtml::textField('reject_counts['.$data->id.']',$data->reject_count,array(
							'style'=>'width:50px;', 
						));
		        	},
				),
				array(
					'name'=>'Good Count',
					'type'=>'raw',
		        	'value'=>function($data,$row){
						return CHtml::textField('good_counts['.$data->id.']',$data->good_count,array(
							'style'=>'width:50px;', 
						));
		        	},
				),
				array(
					'header' => 'Bagger',
					'type' => 'raw',
					'value' => function($data, $row){					
						$returnString = CHtml::textField('bag_user_names['.$data->id.']',User::getFullNameProper($data->bagger_id),array(
							"style"=>"width:110px;",
							"class"=>"autocomplete-user-input",
							"autocomplete"=>"off",
						));
							
						$returnString.= CHtml::hiddenField('bag_user_ids['.$data->id.']',$data->bagger_id, array("class"=>"user-id-input"));
					
						return $returnString;
					},
				),
			),
			'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
			'pager' => array(
				'cssFile' => false,
			)
		)); 
		?>
	</div>

	<?php $this->endWidget(); ?> <!--  EDIT Bagging DETAILS FORM -->
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
<?php endif;?>	

<script type="text/javascript">
function reloadBlankingGrid(data) {	 
    if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
    	try
    	{
    	   var blankStats = $.parseJSON(data);
    	   var alertString = blankStats.length+' blanking dates were edited. New data for the dates is: \n';
    	   blankStats.forEach(function(blankStat) {
    		   alertString += blankStat.date + ' <> Rejects ' + blankStat.reject_count + ' <> Good ' + blankStat.good_count +  '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('blanking-grid');
    	}
    	catch(e)
    	{
    		$('#createelectrode-form').prepend(data);
    		console.log(e.message);
    	}
    }
}

function reloadBaggingGrid(data) {	 
    if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
    	try
    	{
    	   var bagStats = $.parseJSON(data);
    	   var alertString = bagStats.length+' bagging dates were edited. New data for the dates is: \n';
    	   bagStats.forEach(function(bagStat) {
    		   alertString += bagStat.date + ' <> Rejects ' + bagStat.reject_count + ' <> Good ' + bagStat.good_count +  '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('bagging-grid');
    	}
    	catch(e)
    	{
    		$('#createelectrode-form').prepend(data);
    		console.log(e.message);
    	}
    }
}

$(function($) {
	$(document).on('keyup', 'input', function(e){
        if(e.which==39)
                    $(this).closest('td').next().find('input').focus();
        else if(e.which==37)
                    $(this).closest('td').prev().find('input').focus();
        else if(e.which==40)
                    $(this).closest('tr').next().find('td:eq('+$(this).closest('td').index()+')').find('input').focus();
        else if(e.which==38)
                    $(this).closest('tr').prev().find('td:eq('+$(this).closest('td').index()+')').find('input').focus();
	});
	
	jQuery('body').on('focus', '.hasDatePicker', function(event) {
		$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
	});

	jQuery(document).on('keydown', '.autocomplete-user-input', function(event) {
		$(this).autocomplete({
			'select': function(event, ui){
				//if single user checkbox set all inputs to selected user
				if ($('#singleUser').prop('checked')){
					$('.user-id-input').attr("value", ui.item.id);
					$('.autocomplete-user-input').val(ui.item.value);
				}else{
					var id = event.target.id.toString().replace("names","ids");
					$("#"+id).attr("value", ui.item.id);
				}
			},
			'source':'/ytpdb/user/ajaxUserSearch'
		});
	});
	
});

function showBlankingDetails(){

	//unhide the spares selection wrapper
	$('#blanking-wrapper').show();
	$("#blanking-dialog").dialog("open");
}

function submitEditBlanking(){

	$('.errorSummary').remove();
	// populate the spare cell serial dropdown
	
	$.ajax({
		type:'post',
		url: '<?php  echo $this->createUrl('electrode/ajaxeditblankingstats'); ?>',
		data:$('#blanking-form').serialize(),
		success: function(data){
			$('#blanking-dialog').dialog('close');
			reloadBlankingGrid(data);
		},
	});
}

function showBaggingDetails(){

	//unhide the spares selection wrapper
	$('#bagging-wrapper').show();
	$("#bagging-dialog").dialog("open");
}

function submitEditBagging(){

	$('.errorSummary').remove();
	// populate the spare cell serial dropdown
	
	$.ajax({
		type:'post',
		url: '<?php  echo $this->createUrl('electrode/ajaxeditbaggingstats'); ?>',
		data:$('#bagging-form').serialize(),
		success: function(data){
			$('#bagging-dialog').dialog('close');
			reloadBaggingGrid(data);
		},
	});
}
</script>
