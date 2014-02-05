<?php
/* @var $this NcrController */
/* @var $ncrModel Ncr */

$this->breadcrumbs=array(
	'QA'=>array('/qa'),
	'NCR'=>array('index'),
	'Put Cells On NCR'
);

$this->menu=array(
	array('label'=>'Dispo Cells on NCR', 'url'=>array('dispositioncells')),
	array('label'=>'View All NCRs', 'url'=>array('index')),
	array('label'=>'NCR Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

?>

<h1>Put Cells on NCR</h1>
<p>*All Cells that have been stacked will be visible.</p>

<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true, // no need for this.
	'enableClientValidation'=>true,
	'id'=>'ncr-form',
)); ?>

<div class="form" style="margin-bottom:15px;">
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php echo $form->errorSummary($ncrModel); ?>
	
	<div class="left-form">
		<div class="row">
	        <?php echo CHtml::label('Add to Existing NCR', 'Ncr_id'); ?>
	        <?php echo $form->dropDownList($ncrModel, 'id', 
								CHtml::listData(Ncr::model()->findAll(), 'id','number'), 
								array(
									'prompt'=>' -Select NCR- ',
									'style'=>'width:152px',
								)); ?>
	        <?php echo $form->error($ncrModel,'id'); ?>
	    </div>
	</div>
	<div class="right-form">
		<div class="row">
			<?php echo CHtml::label('New NCR Number', 'Ncr_number', array('style'=>'display:none')); ?>
			<?php echo $form->textField( $ncrModel,'number', 
								array(
									'style'=>'width:150px; display: none;',
								)); ?>
			<?php echo CHtml::link('Create New NCR','#',array('class'=>'toggle-link')); ?><div class="clear"></div>
			<?php echo $form->error($ncrModel,'number'); ?>
		</div>
	</div>
	<div class="clear"></div>
	
	<?php echo CHtml::ajaxSubmitButton('Filter',array('ncr/putcellsonncr'), array(),array("style"=>"display:none;")); ?>
	<?php echo CHtml::ajaxSubmitButton('Submit',array('ncr/ajaxputcellsonncr'), array('success'=>'ncrComplete'), array("id"=>"submit-button")); ?>
	
</div>

<div class="shadow border" >
<span>*NCRs for Cells that are Open/Scrapped/Eng Use only are bold and red.</span>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ncr-grid',
	'dataProvider'=>$cellModel->search(),
	'filter'=>$cellModel,
	'columns'=>array(
		array(
            'id'=>'autoId',
            'class'=>'CCheckBoxColumn',
            'selectableRows' => '50',   
        ),
        array(
			'name'=>'serial_search',
			'value'=>'$data->kit->getFormattedSerial()',
		),
        array(
			'name'=>'celltype_search',
			'value'=>'$data->kit->celltype->name',
		),
       array(
			'name'=>'refnum_search',
			'value'=>'$data->refNum->number',
		),
		array(
			'name'=>'ncr_search',
			'type'=>'html',
			'value'=>'$data->getNCRLinks()',
		),
		'location',
    ),
    //'htmlOptions'=>array('class'=>'shadow grid-view'),
	'cssFile' => Yii::app()->baseUrl . '/css/styles.css',
	'pager'=>array(
		'cssFile' => false,
	),
)); 
?>
</div>
 
<?php $this->endWidget(); ?> <!--  NCR FORM -->

<script type="text/javascript">
function ncrComplete(data) {	 
    if(data=='hide')
    {
    	$('.errorSummary').remove();
    }
    else
    {
    	try
    	{
    	   var results = $.parseJSON(data);
    	   var alertString = results.cells.length +' cells were added to NCR'+ results.ncr + '. Serial numbers: \n';
    	   results.cells.forEach(function(cell) {
    		   alertString += cell.serial  + '\n';
    	   });
    	   alert(alertString);
    	   location.reload();
    	}
    	catch(e)
    	{
    		$('#ncr-form').prepend(data);
    		console.log(e.message);
    	}
    }
}

$(document).ready(function($) {
	var numElement = $('#Ncr_number');
	var idElement = $('#Ncr_id');
	
	$('.toggle-link').on('click', function(){
		
		numElement.toggle();
		$("label[for='Ncr_number']").toggle();

		if(numElement.is(':visible')){
			idElement.val('');
			idElement.attr('disabled', 'disabled');
			$(this).text('Use Existing');
		} else {
			idElement.val('');
			idElement.removeAttr('disabled');
			$(this).text('Create New NCR');
			numElement.val('');
			$('#Ncr_number_em_').hide();
		}
	});

	$('#submit-button').on('click', function(event) {
		var noneChecked = true;
		$('.errorSummary').remove();
		
		$('input[name="autoId[]"]').each(function () {
	        if (this.checked) {
	            noneChecked = false; 
	        }
		});

		if(noneChecked)
		{
			alert('You must select at least one cell to put on NCR');
			return false;
		}

		if(numElement.val() == '' && idElement.val() == ''){
			alert('You must select an Existing NCR or create a new NCR to put cells on');
			return false;
		}

		if(numElement.val() != '' && idElement.val() != ''){
			alert('You must select only Existing NCR or create a new NCR to put cells on. Not both.');
			return false;
		}
	});
	
});
</script>