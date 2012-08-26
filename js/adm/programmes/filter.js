$(document).ready(function(){
	$("#art_filter").click(function(){
		var nArtId = $('#art_id').val();
		var sTitle = $("#art_title").val();
		var sCategoryTitle = $("#art_category_title").val();
		var sSpaTitle = $("#art_spa_title").val();
		var sCityTitle = $("#art_city_title").val();
		var sArtStatus = $('#art_status').val();
		var nPerPage = $('#art_per_page').val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_programmes/programmes/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nArtId + '~' + sTitle + '~' + sCategoryTitle + '~' + sSpaTitle + '~' + sCityTitle + '~' + sArtStatus + '/0';
		return false;
	});
	$("#art_filter_reset").click(function(){
		var nPerPage = parseInt($('#art_per_page').val());
		var nOrder = parseInt($('#art_order').val());
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_programmes/programmes/' + nPerPage + '/' + nOrder + '/' + sDirect + '/~~~~~/0';
		return false;
	});
	$("#art_per_page").change(function(){
		var nArtId = $('#art_id').val();
		var sTitle = $("#art_title").val();
		var sCategoryTitle = $("#art_category_title").val();
		var sSpaTitle = $("#art_spa_title").val();
		var sCityTitle = $("#art_city_title").val();
		var sArtStatus = $('#art_status').val();
		var nPerPage = $(this).val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_programmes/programmes/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nArtId + '~' + sTitle + '~' + sCategoryTitle + '~' + sSpaTitle + '~' + sCityTitle + '~' + sArtStatus + '/0';
		return false;
	});
	
	$('.save').click(function(){
		var input = $(this).prev();
		input.addClass('ui-autocomplete-loading');
		var id = parseInt(input.attr('id'));
		var order = parseInt(input.val());
		if(isNaN(order)){
			order = 0;
		}
		$.post(site_url + 'adm_programmes/programme_order_save', {id: id, order: order}, function(data){
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
		$( "#art_title" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_programmes/programme_autocomplete_title", request, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					}
				});
			}
		});
	});
	$(function() {
		var cache = {},
			lastXhr;
		$( "#art_category_title" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_categories/category_autocomplete_title", request, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					}
				});
			}
		});
	});
	$(function() {
		var cache = {},
			lastXhr;
		$( "#art_spa_title" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_spas/spa_autocomplete_title", request, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					}
				});
			}
		});
	});
	$(function() {
		var cache = {},
			lastXhr;
		$( "#art_city_title" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_locations/city_autocomplete_title", request, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					}
				});
			}
		});
	});
});