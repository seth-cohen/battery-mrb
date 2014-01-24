<?php
/* @var $this NcrController */
/* @var $ncrModel Ncr */

$this->breadcrumbs=array(
	'QA'=>array('/qa'),
	'NCR'=>array('index'),
	'Put Cells On NCR'
);

$this->menu=array(
	array('label'=>'Put Cells on NCR', 'url'=>array('dispocellsonncr')),
	array('label'=>'View All NCRs', 'url'=>array('dispocellsonncr')),
	array('label'=>'NCR Admin', 'url'=>array('admin')),
);

?>

<h1>Put Cells on NCR</h1>
<span>*All cells that are on at least one NCR will be visible.  You can search by NCR number or any other field</span>

<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true, // no need for this.
	'enableClientValidation'=>true,
	'id'=>'ncr-form',
)); ?>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ncr-grid',
	'dataProvider'=>$cellModel->searchOnNCR(),
	'filter'=>$cellModel,
	'columns'=>array(
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
			'header'=>'NCR to Dispo',
			'type'=>'raw',
			'value'=>function($data,$row) {
				return CHtml::dropDownList('Ncr['.$data->id.']', '', 
					CHtml::listData($data->ncrs, 'id', 'number'), 
					array(
							"prompt"=>"-Select NCR-",
							"class"=>"ncr-dropdown",
							"onChange"=>"ncrSelected(this)",
							"style"=>"width:100px",
							"data-cell-id"=>$data->id,
					)
				);
			},
		),
		array(
			'header'=>'Disposition',
			'type'=>'raw',
			'value'=>'CHtml::dropDownList("Dispo[$data->id]", "", 
				array(
					"0"=>"Open",
					"1"=>"Scrap",
					"2"=>"Eng Use Only",
					"3"=>"Accept",
					"4"=>"Use As Is",
				), 
				array(
					"prompt"=>"-N/A-",
					"onChange"=>"dispoSelected(this)",
					"style"=>"width:100px",
					"disabled"=>"disabled",
					"data-cell-id"=>$data->id,
				)
			)',
		),
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
$(document).ready(function($) {

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
	});
});

function ncrSelected(sel)
{
	var dispo_id = sel.id.toString().replace("Ncr","Dispo");
	
	var ncr_id = $('option:selected', $(sel)).attr("value");
	var cell_id =$(sel).data("cell-id");

	$.ajax({
		url: '<?php echo $this->createUrl('/ncr/ajaxgetncrcelldispo'); ?>',
		type: 'POST',
		data: 
		{
			id: ncr_id,
			cell_id: cell_id,
		},
		success: function(data) {
			alert(data);
			$('#'+dispo_id).removeAttr('disabled');
			$('#'+dispo_id).val(data);
		},
	});
}

function dispoSelected(sel)
{
	var ncrElement = sel.id.toString().replace("Dispo","Ncr");
	
	var ncr_id = $('option:selected', $('#'+ncrElement)).attr("value");
	var cell_id =$(sel).data("cell-id");
	var dispo = $('option:selected', $(sel)).attr("value");
	
	$.ajax({
		url: '<?php echo $this->createUrl('/ncr/ajaxsetdispo'); ?>',
		type: 'POST',
		data: 
		{
			id: ncr_id,
			cell_id: cell_id,
			dispo: dispo,
		},
		success: function(data) {
			if(data == '1'){
				$(sel).css('color', 'green');
			}else{
				$(sel).css('color', 'red');
			}
		},
	});	
}
</script>