$(document).ready(function(){
	$("#art_filter").click(function(){
		var nArtId = $('#art_id').val();
		var sTitle = $("#art_title").val();
		var sRegionTitle = $("#art_region_title").val();
		var sCountryTitle = $("#art_country_title").val();
		var nPerPage = $('#art_per_page').val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_trans/cities/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nArtId + '~' + sTitle + '~' + sRegionTitle + '~' + sCountryTitle + '/0';
		return false;
	});
	$("#art_filter_reset").click(function(){
		var nPerPage = parseInt($('#art_per_page').val());
		var nOrder = parseInt($('#art_order').val());
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_trans/cities/' + nPerPage + '/' + nOrder + '/' + sDirect + '/~~~/0';
		return false;
	});
	$("#art_per_page").change(function(){
		var nArtId = $('#art_id').val();
		var sTitle = $("#art_title").val();
		var sRegionTitle = $("#art_region_title").val();
		var sCountryTitle = $("#art_country_title").val();
		var nPerPage = $(this).val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_trans/cities/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nArtId + '~' + sTitle + '~' + sRegionTitle + '~' + sCountryTitle + '/0';
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

				lastXhr = $.getJSON( site_url + "adm_trans/city_autocomplete_title", request, function( data, status, xhr ) {
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
		$( "#art_region_title" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_trans/region_autocomplete_title", request, function( data, status, xhr ) {
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
		$( "#art_country_title" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_trans/country_autocomplete_title", request, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					}
				});
			}
		});
	});
});