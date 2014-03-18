<?php
/* @var $this KitController */
/* @var $dataProvider CArrayDataProvider */
/* @var $model Cell */

$this->breadcrumbs=array(
	'Kits'=>array('index'),
	'Create Multiple Kits',
);

$this->menu=array(
	array('label'=>'View All Kits', 'url'=>array('index')),
	array('label'=>'Kit Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
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

<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'id'=>'kit-form',
)); ?>

<div class="form" style="margin-bottom:15px;">
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
			<?php echo $form->dropDownList($model, 'ref_num_id', 
							CHtml::listData(RefNum::model()->findAll(array('condition'=>'id <> 70')), 'id','number'), 
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
							CHtml::listData(Electrode::model()->anodes()->notGeneric()->findAll(), 'id','lot_num'), 
							array(
								'multiple'=>'multiple',
								'prompt'=>' -Select Anode Lots- ',
								'style'=>'width:152px',
								'size'=>5,
							)); ?>
			<?php echo $form->error($model,'anodeIds'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'celltype_id'); ?>
			<?php echo $form->dropDownList($model, 'celltype_id', 
							CHtml::listData(Celltype::model()->inOrder()->findAll(), 'id','name'),
							array(
								'prompt'=>'-Select Type-',
								'class'=>'celltype-dropdown',
								'onChange'=>'typeSelected(this)',
								'style'=>'width:152px',
							)); ?>
			<?php echo $form->error($model,'celltype_id'); ?>
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
							CHtml::listData(Electrode::model()->cathodes()->notGeneric()->findAll(), 'id','lot_num'), 
							array(
								'multiple'=>'multiple',
								'prompt'=>' -Select Cathode Lots- ',
								'style'=>'width:152px',
								'size'=>5,
							)); ?>
			<?php echo $form->error($model,'cathodeIds'); ?>
		</div>
		<div class="row">
			<div style="padding-top:10px; width:300px"><span id="last-serial"></span></div>
		</div>
	</td>
</tr></table>
</div>

<?php echo CHtml::checkBox('singleUser', true)?><span style="margin-left:5px">Assign to Single User</span>


<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'kitting-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
            'id'=>'index',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50', 
			'value'=>'$data["id"]', 
        ),
		array(
			'name'=>'No.',
			'value'=>'$data["id"]',
		),
		array(
			'header' => 'Serial No. <input id="auto-fill" type="submit" name="auto-fill" value="AutoFill">',
			'type' => 'raw',
			'value'=>function($data,$row){
				return 
					'<span></span>'.
					CHtml::textField('serials['.$data["id"].']','',array(
						'style'=>'width:100px;',
						'class'=>'serial-nums', 
					));
			},	
			'htmlOptions'=>array(
				'style'=>'width:200px;',
				'class'=>'serial-cell',
			),
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
				return CHtml::textField('dates['.$data["id"].']',date('Y-m-d',time()),array(
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
    	   var kits = $.parseJSON(data);
    	   var alertString = kits.length+' kits were kitted. Serial numbers: \n';
    	   kits.forEach(function(kit) {
    		   alertString += kit.serial + ' by ' + kit.kitter + '\n';
    	   });
    	   alert(alertString);
    	   location.reload();
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

$(document).ready(function($) {

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

	$(document).on('click', '#auto-fill', function(event) {
		if($('#serials_1').val() == ''){
			alert('Enter the first serial number in line 1 before clicking this button');
			$('.serial-nums').val('');
		}
		else{
			var serial_one = $('#serials_1').val();
			while(serial_one.length < 4){ // zero pad with leading 0s if less than 4
				serial_one = '0' + serial_one;
				alert('in loop');
			}
			$('#serials_1').val(serial_one);
			var serial_one = +serial_one; // convert to a number
			
			$('.serial-nums').not('#serials_1').each(function(key, value){
				var new_serial = '' + (serial_one + key+1);
				while(new_serial.length < 4){	// increment each additional serial number by 1
					new_serial = '0' + new_serial;
				}
				$(this).val(new_serial);
			});
		}
		return false;
	});

	$('#submit-button').on('click', function(event) {
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
			return false;
		}
	});

	jQuery('body').on('focus', '.hasDatePicker', function(event) {
		$(this).datepicker({'showAnim':'slideDown','changeMonth':true,'changeYear':true,'dateFormat':'yy-mm-dd'});
	});
});

function typeSelected(sel)
{
	var celltype = $('option:selected', $(sel)).text();
	var celltype_id = $('option:selected', $(sel)).val();

	$('.serial-cell span').text(celltype+'-');

	$.ajax({
		type:'get',
		url: '<?php echo $this->createUrl('kit/lastserial'); ?>',
		data:
		{
			celltype_id: celltype_id.toString(),
		},
		success: function(data){
			$('#last-serial').text(
					"Highest serial number used for this cell type was: " +
					celltype + "-" + data
			 );
		},
	});
}

function refSelected(sel)
{
	var ref = $('option:selected', $(sel)).text();
	$("#Kit_eap_num").attr("value","EAP "+ ref + " ADD ");
	$("#Kit_eap_num").focus();
}


</script>
