var currentPage = 0;

$(document).ready(function($) {
	
	// show the battery type form if there were errors in creating new model
	if (!($('#batterytype-form_es_').css('display') == 'none') )
	{
		$('#batterytype-wrapper').show();
	}

	
	$('#batterytype-link').on('click', function(event) {
		$('#batterytype-wrapper').show();
	});

	$(document).on('click', '#next-module-link', function(event){
		if (!$('#cellselection-wrapper-'+(currentPage+1)).length){
			//do nothing
		} else {
			if (!$('#cellselection-wrapper-'+(currentPage+2)).length){
				//do nothing
				$('#next-module-link').hide();
			}
			if(currentPage == 0)
				$('#previous-module-link').show();
			
			//animate current grid left
			var $element = $('#cellselection-wrapper-'+currentPage);
			var right = $element.parent().width()+20;
			$element.animate({
				right: right,
			},{
				easing: 'easeInExpo',
			});
			currentPage += 1;
			
			//animate next grid left to center
			var right = $element.parent().width()/2-$element.width()/2;
			$element = $('#cellselection-wrapper-'+currentPage);
			$element.animate({
				right: right,
			},{
				duration: 600,
				easing: 'easeOutBounce',
			});
		}
		return false;
	});

	$(document).on('click', '#previous-module-link', function(event){
		if (!$('#cellselection-wrapper-'+(currentPage-1)).length){
			//do nothing
			$('#previous-module-link').hide();
		} else {
			if (!$('#cellselection-wrapper-'+(currentPage-2)).length){
				//do nothing
				$('#previous-module-link').hide();
			}
			if(!$('#cellselection-wrapper-'+(currentPage+1)).length)
				$('#next-module-link').show();
			
			//animate current grid left
			var $element = $('#cellselection-wrapper-'+currentPage);
			var right = -$element.parent().width()-20;
			$element.animate({
				right: right,
			},{
				easing: 'easeInExpo',
			});
			currentPage -= 1;
			
			//animate next grid right to center
			var right = $element.parent().width()/2-$element.width()/2;
			$element = $('#cellselection-wrapper-'+currentPage);
			$element.animate({
				right: right,
			},{
				duration: 600,
				easing: 'easeOutBounce',
			});
		}
		return false;
	});
});

function refSelected(sel)
{
	var ref = $('option:selected', $(sel)).text();
	$("#Battery_eap_num").val("EAP "+ ref + " ADD ").focus();
}

function typeSelected(sel, urlFormContent, urlCellsAvailable)
{
	var type_id = $('option:selected', $(sel)).val();
	$.ajax({
		type:'get',
		url: urlFormContent,
		data:
		{
			type_id: type_id.toString(),
		},
		success: function(data){
			currentPage = 0;
			$('#selection-container').html(data).css('height','600px');
			$('#previous-module-link').hide();
			if (!$('#cellselection-wrapper-'+(currentPage+1)).length){
				//do nothing
				$('#next-module-link').hide();
			}

			// populate the cell serial dropdown
			$.ajax({
				type:'get',
				url: urlCellsAvailable,
				data:
				{
					type_id: type_id.toString(),
				},
				success: function(data){
					$('.cell-dropdown').html(data);
				},
			});
		},
	});
}

function cellSelected(sel, urlAjaxCellSelected)
{
	var values = [];
	var type_id = $('option:selected', '#Battery_batterytype_id').val();
	
	//($('select.cell-dropdown').serialize());  << use this for the submit function
	$('option:selected', '.cell-dropdown').each(function(index){
		if($(this).val())
		{
			values.push($(this).val());
		}
	});
	console.log(values);
	
	$.ajax({
		type:'get',
		url: urlAjaxCellSelected,
		data:
		{
			type_id: type_id.toString(),
			values: values,
		},
		success: function(data){
			$('.cell-dropdown').each(function(event){
				if(!$(this).val()){
					$(this).html(data);
				}
			});
		},
	});
	
}