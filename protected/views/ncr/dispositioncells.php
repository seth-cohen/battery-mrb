<?php
/* @var $this NcrController */
/* @var $ncrModel Ncr */

$this->breadcrumbs=array(
	'QA'=>array('/qa'),
	'NCR'=>array('index'),
	'Put Cells On NCR'
);

$this->menu=array(
	array('label'=>'Put Cells on NCR', 'url'=>array('putcellsonncr')),
	array('label'=>'View All NCRs', 'url'=>array('index')),
	array('label'=>'NCR Admin', 'url'=>array('admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
);

?>

<h1>Disposition Cells on NCRs</h1>
<span>*All cells that are on at least one NCR will be visible.  You can search by NCR number or any other field</span>

<?php $form=$this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true, // no need for this.
	'enableClientValidation'=>true,
	'id'=>'ncr-form',
)); ?>

<div class="shadow border" >
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ncr-grid',
	'dataProvider'=>$ncrCellModel->search(),
	'filter'=>$ncrCellModel,
	'columns'=>array(
		array(
			'name'=>'ncr_search',
			'type'=>'raw',
			'value'=>function($data, $row){
				return CHtml::link('NCR-'.$data->ncr->number, Yii::app()->createUrl('ncr/view', array('id'=>$data->ncr->id)), array(	));
			},	
		),
        array(
			'name'=>'serial_search',
			'value'=>'$data->cell->kit->getFormattedSerial()',
		),
       array(
			'name'=>'refnum_search',
			'value'=>'$data->cell->refNum->number',
		),
		array(
			'name'=>'disposition_string',
			'type'=>'raw',
			'value'=>function($data, $row){
				return 
				CHtml::activeDropDownList($data,"disposition",
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
						'data-cell-id'=>$data->cell->id,
						'data-ncr-id'=>$data->ncr->id,
					)
				);
			},
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

function dispoSelected(sel)
{
	var ncrElement = sel.id.toString().replace("Dispo","Ncr");
	
	var ncr_id = $(sel).data("ncr-id");
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
			var message;
			if(data == '1'){
				message = $("<br/><span style='color:green'>Change Successful</span>");
				$(sel).css('border', '2px solid green');
				$(sel).parent().append(message);
				setTimeout(function() {
					$(sel).css('border', '1px solid');
					message.remove();
				}, 2000);
			}else{
				message = $("<br/><span style='color:red'>Change Failed</span>");
				$(sel).css('border', '2px solid red');
				$(sel).parent().append(message);
				setTimeout(function() {
					$(sel).css('border', '1px solid');
					message.remove();
				}, 2000);
			}
		},
	});	
}
</script>