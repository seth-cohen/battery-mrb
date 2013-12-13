var currentPage = 0;
var numCells = 99999;
var urlSuccess = '';

function checkSelection(data){
	if(data=='hide')
	{
		$('.errorSummary').remove();
	}
	else
	{
		try
		{
		   var batteryResult = $.parseJSON(data);
		   var alertString = 'You selected cells for ' + batteryResult.batterytype + ' SN: ' + batteryResult.serial_num;
		   alertString += '\n' + batteryResult.num_spares + ' spares were selected.\n\nWould you like to select another battery?';
		   if(confirm(alertString)==false){
				window.location  = urlSuccess;
			} else {
				 window.location.reload();
			}
		}
		catch(e)
		{
			$('#battery-form').prepend(data);
			console.log(e.message);
		}
	}
}

$(document).ready(function($) {
	
	// show the battery type form if there were errors in creating new model
	if (!($('#batterytype-form_es_').css('display') == 'none') )
	{
		$('#batterytype-wrapper').show();
	}

	
	$('#batterytype-link').on('click', function(event) {
		$('#batterytype-wrapper').show();
	});
	
/*	
	$(document).on('focus', '.cell-dropdown', function(event){
		// store value and text for use later 
		var el = $(this);
		el.data('prevValue', this.value);
		el.data('prevText', this.options[this.selectedIndex].text);
		console.log(el.data('prevValue')+'-'+el.data('prevText'));
	});
*/
	
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
	
	jQuery('#submit-button').on('click', function(event) {
		$('.errorSummary').remove();
		/* make sure that enough cells were selected */
		var allSelected = true;
		var sparesSelected = false;
		
		$('.cell-dropdown.cells ').each(function(index){
			if( !(this.value > 0) ){
				allSelected = false;
			}
		});
		$('.cell-dropdown.spares ').each(function(index){
			if( (this.value > 0) ){
				sparesSelected = true;
			}
		});
		if (allSelected == false){
			alert('You have not selected enough cells');
			return false;
		}
		if (sparesSelected == false){
			var confirmString = 'You have not selected any spare cells.  Do you still want to continue?';
			if (confirm(confirmString)==false){
				return false;
			}
		}
		
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
	var partNum = $('option:selected', $(sel)).data('partnum');
	
	$('#part-num').text('('+partNum+')');
	
	$.ajax({
		type:'get',
		url: urlFormContent,
		data:
		{
			type_id: type_id.toString(),
		},
		success: function(data){
			currentPage = 0;
			$('#selection-container').html(data).css('height','400px');
			
			$('#cellspares-wrapper').show();
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

function cellSelected(sel)
{
/*	
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
*/
	/* remove the selected value from the other dropdowns */
	var el = $(sel);
	var selectedValue = $('option:selected', el).val();
	if(selectedValue!=''){
		$('.cell-dropdown').not(el).each(function(index){
			$('option[value="'+selectedValue+'"]', this).remove();
		});	
	} 
	if (el.data('prevValue')){
		/* add previous value back to selects */
		$('.cell-dropdown').not(el).each(function(index){
			$(this).append($('<option>', {value : el.data('prevValue')})
				.text(el.data('prevText')));
		});	
	}
	el.data('prevValue', sel.value);
	el.data('prevText', sel.options[sel.selectedIndex].text);
}
