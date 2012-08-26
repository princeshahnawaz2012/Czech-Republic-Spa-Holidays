$(document).ready(function(){
	$("#art_filter").click(function(){
		var nArtId = $('#art_id').val();
		var sTitle = $("#art_title").val();
		var sArtStatus = $('#art_status').val();
		var nPerPage = $('#art_per_page').val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_locations/countries/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nArtId + '~' + sTitle + '~' + sArtStatus + '/0';
		return false;
	});
	$("#art_filter_reset").click(function(){
		var nPerPage = parseInt($('#art_per_page').val());
		var nOrder = parseInt($('#art_order').val());
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_locations/countries/' + nPerPage + '/' + nOrder + '/' + sDirect + '/~~/0';
		return false;
	});
	$("#art_per_page").change(function(){
		var nArtId = $('#art_id').val();
		var sTitle = $("#art_title").val();
		var sArtStatus = $('#art_status').val();
		var nPerPage = $(this).val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_locations/countries/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nArtId + '~' + sTitle + '~' + sArtStatus + '/0';
		return false;
	});
	
	$('.save').click(function(){
		var input = $(this).prev();
		input.css('border-color', 'lightgreen').addClass('ui-autocomplete-loading');
		var id = parseInt(input.attr('id'));
		var order = parseInt(input.val());
		if(isNaN(order)){
			order = 0;
		}
		$.post(site_url + 'adm_locations/country_order_save', {id: id, order: order}, function(data){
			if( data == 'ok' ){
				input.css('border-color', '#DDD').removeClass('ui-autocomplete-loading');
			}
			else{
				input.css('border-color', 'lightcoral').removeClass('ui-autocomplete-loading');
			}
		}, 'text').error(function(){
			input.css('border-color', 'lightcoral').removeClass('ui-autocomplete-loading');
		});
		return false;
	});
	$(function() {
		var cache = {},
			lastXhr;
		$( "#art_title" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_locations/country_autocomplete_title", request, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					}
				});
			}
		});
	});
});