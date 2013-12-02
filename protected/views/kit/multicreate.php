<?php
/* @var $this CellController */
/* @var $dataProvider CArrayDataProvider */
/* @var $model Cell */

$this->breadcrumbs=array(
	'Test Lab'=>array('/testlab'),
	'Cell Formation',
);

$this->menu=array(
	array('label'=>'View All Kits', 'url'=>array('index')),
	array('label'=>'Manage Kit', 'url'=>array('admin')),
);
?>

<h1>Create Kits (Multi)</h1>
<?php
/* ionclude JQuery scripts to allow for autocomplte */
Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
Yii::app()->clientScript->registerCssFile(
        Yii::app()->clientScript->getCoreScriptUrl().
        '/jui/css/base/jquery-ui.css'
);
?>

<div class="form" style="margin-bottom:15px;">
<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions' => array(
		'validateOnSubmit' => true,
	),
	'id'=>'kit-form',
)); ?>
<p class ="note">
*Create multiple kits for a single cell type that use exactly the same electrode lots. <br/>
If any additional or fewer electrode lots are being used an additional form needs to be filled
out.
</p>
<?php echo $form->errorSummary($model); ?>

<table class="double-form"><tr>
	<td>
		<div class="row">
			<?php echo $form->labelEx($model,'ref_num_id'); ?>
			<?php echo CHtml::activeDropDownList($model, 'ref_num_id', 
							CHtml::listData(RefNum::model()->findAll(), 'id','number'), 
							array(
								'prompt'=>' -Select Reference No.- ',
								'onchange'=>'refSelected(this)',
								'style'=>'width:152px'
							)); ?>
			<?php echo $form->error($model,'ref_num_id'); ?>
		</div>
		<div class="row multidrop5">
			<?php echo CHtml::label('Anode Lots', 'Kit_anodeIds'); ?>
			<?php echo CHtml::DropDownList('Kit[anodeIds][]', $model->anodeIds, 
							CHtml::listData(Electrode::model()->anodes()->findAll(), 'id','lot_num'), 
							array(
								'multiple'=>'multiple',
								'prompt'=>' -Select Anode Lots- ',
								'style'=>'width:152px',
								'size'=>5,
							)); ?>
			<?php echo $form->error($model,'anodeIds'); ?>
		</div>
	</td>
	<td>
		<div class="row">
			<?php echo $form->labelEx($model,'eap_num'); ?>
			<?php echo $form->textField($model,'eap_num', array(
							'style'=>'width:150px;',
						)); ?> <span style="padding-left:10px"; ><em><b>ex.</b></em> EAP 00999 ADD 1A</span>
			<?php echo $form->error($model,'eap_num'); ?>
		</div>
		<div class="row multidrop5">
			<?php echo CHtml::label('Cathode Lots', 'Kit_cathodeIds'); ?>
			<?php echo CHtml::DropDownList('Kit[cathodeIds][]', $model->cathodeIds, 
							CHtml::listData(Electrode::model()->cathodes()->findAll(), 'id','lot_num'), 
							array(
								'multiple'=>'multiple',
								'prompt'=>' -Select Cathode Lots- ',
								'style'=>'width:152px',
								'size'=>5,
							)); ?>
			<?php echo $form->error($model,'cathodeIds'); ?>
		</div>
	</td>
</tr></table>
</div>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kitting-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'header' => 'Mark Bad',
            'id'=>'badId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50', 
			'value'=>'$data["id"]', 
        ),
		array(
			'name'=>'No.',
			'value'=>'$data["id"]',
		),
		array(
			'header' => 'Cell Type',
			'type' => 'raw',
			'value'=>function($data,$row){
				return 
				CHtml::dropDownList('celltypes['.$data['id'].']', '', 
					CHtml::listData(Celltype::model()->findAll(), 'id','name'),
					array(
						'prompt'=>'-Select Type-',
						'class'=>'celltype-dropdown',
						'onChange'=>'typeSelected(this)',
						'style'=>'width:100px',
					)	
				);
			},	
		),
		array(
			'header' => 'Serial No.',
			'type' => 'raw',
			'value'=>function($data,$row){
				return CHtml::textField('serials[$data["id"]','',array(
					'style'=>'width:100px;', 
				));
			},	
		),
		array(
			'header' => 'Kitter',
			'type' => 'raw',
			'value' => array($this, 'getUserInputTextField'),
		),
		array(
			'header' => 'Kitting Date',
			'type' => 'raw',
			'value'=>function($data,$row){
				return CHtml::textField('dates[$data["id"]',date('Y-m-d',time()),array(
					'style'=>'width:100px;', 
					'class'=>'hasDatePicker',
				));
			},	
		),
	),
	'htmlOptions'=>array('width'=>'100%'),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); ?>
</div>
<script>
function reloadGrid(data) {	
	if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
    	try
    	{
    	   var cells = $.parseJSON(data);
    	   var alertString = cells.length+' cells were put on formation. Serial numbers: \n';
    	   cells.forEach(function(cell) {
    		   alertString += cell.serial + ' on ' + cell.cycler + '-' + cell.channel + '\n';
    	   });
    	   alert(alertString);
    	   $.fn.yiiGridView.update('kit-grid');
    	}
    	catch(e)
    	{
    		$('#kit-form').prepend(data);
    		console.log(e.message);
    	}
    }
}
</script>
<?php echo CHtml::ajaxSubmitButton('Filter',array('kit/multicreate'), array(),array("style"=>"display:none;")); ?>
<?php echo CHtml::ajaxSubmitButton('Submit',array('kit/ajaxmulticreate'), array('success'=>'reloadGrid'), array("id"=>"submit-button")); ?>

<?php $this->endWidget(); ?>

<script type="text/javascript">

jQuery(function($) {
	jQuery('.ui-autocomplete-input').live('keydown', function(event) {
		$(this).autocomplete({
			'select': function(event, ui){
				
				var id = event.target.id.toString().replace("names","ids");
				$('.user-id-input').attr("value", ui.item.id);
				$('.ui-autocomplete-input').val(ui.item.value);
				$('.ui-autocomplete-input').val(ui.item.value);
			},
			'source':'/ytpdb/user/ajaxUserSearch'
		});
	});
});

jQuery('.hasDatePicker').live('focus', function(event) {
	$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
});

function typeSelected(sel)
{
	var celltype_id = $('option:selected', $(sel)).attr("value");

	$('.celltype-dropdown').val(celltype_id);
}

function refSelected(sel)
{
	var ref = $('option:selected', $(sel)).text();
	$("#Kit_eap_num").attr("value","EAP "+ ref + " ADD ");
	$("#Kit_eap_num").focus();
}

jQuery('#submit-button').bind('click', function(event) {
	var noneChecked = true;
	$('.errorSummary').remove();
	
	$('input[type=checkbox]').each(function () {
        if (this.checked) {
            noneChecked = false; 
        }
	});

	if(noneChecked)
	{
		alert('You must select at least one kit to create');
	}
});

</script>
<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 1; display: none;"></ul>
<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
