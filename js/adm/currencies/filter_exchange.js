$(document).ready(function(){
	$("#art_filter").click(function(){
		var nIdFrom = $('#cur_id_from').val();
		var sTitleFrom = $("#cur_title_from").val();
		var nIdTo = $('#cur_id_to').val();
		var sTitleTo = $("#cur_title_to").val();
		var nPerPage = $('#art_per_page').val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_currencies/currencies_exchange/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nIdFrom + '~' + sTitleFrom + '~' + nIdTo + '~' + sTitleTo + '/0';
		return false;
	});
	$("#art_filter_reset").click(function(){
		var nPerPage = parseInt($('#art_per_page').val());
		var nOrder = parseInt($('#art_order').val());
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_currencies/currencies_exchange/' + nPerPage + '/' + nOrder + '/' + sDirect + '/~~~/0';
		return false;
	});
	$("#art_per_page").change(function(){
		var nIdFrom = $('#cur_id_from').val();
		var sTitleFrom = $("#cur_title_from").val();
		var nIdTo = $('#cur_id_to').val();
		var sTitleTo = $("#cur_title_to").val();
		var nPerPage = $(this).val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_currencies/currencies_exchange/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nIdFrom + '~' + sTitleFrom + '~' + nIdTo + '~' + sTitleTo + '/0';
		return false;
	});
	
	$('.save').click(function(){
		var input = $(this).prev();
		input.addClass('ui-autocomplete-loading');
		var from_id = input.attr('id').substr(0, 3);
		var to_id = input.attr('id').substr(4, 3);
		var rate = parseFloat(input.val());
		if(isNaN(rate)){
			alert("Error parse Rate");
			return false;
		}
		$.post(site_url + 'adm_currencies/currency_exchange_rate_save', {from_id: from_id, to_id: to_id, rate: rate}, function(data){
			if( data == 'ok' ){
				input.removeClass('ui-autocomplete-loading').addClass('ajax-success');
				setTimeout(function(){
					input.removeClass('ajax-success');
				}, 1000);
			}
			else{
				input.removeClass('ui-autocomplete-loading').addClass('ajax-error');
				setTimeout(function(){
					input.removeClass('ajax-error');
				}, 1000);
			}
		}, 'text').error(function(){
			input.removeClass('ui-autocomplete-loading').addClass('ajax-error');
			setTimeout(function(){
				input.removeClass('ajax-error');
			}, 1000);
		});
		return false;
	});
	$(function() {
		var cache = {},
			lastXhr;
		$( ".currency_title_filter" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_currencies/currency_autocomplete_title", request, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					}
				});
			}
		});
	});
});