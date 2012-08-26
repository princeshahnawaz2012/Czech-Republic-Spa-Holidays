$(document).ready(function(){
	$("#art_filter").click(function(){
		var nArtId = $('#art_id').val();
		var sLogin = $("#art_login").val();
		var sUsername = $("#art_username").val();
		var sArtStatus = $('#art_status').val();
		var nPerPage = $('#art_per_page').val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_user/listing/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nArtId + '~' + sLogin + '~' + sUsername + '~' + sArtStatus + '/0';
		return false;
	});
	$("#art_filter_reset").click(function(){
		var nPerPage = parseInt($('#art_per_page').val());
		var nOrder = parseInt($('#art_order').val());
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_user/listing/' + nPerPage + '/' + nOrder + '/' + sDirect + '/~~~/0';
		return false;
	});
	$("#art_per_page").change(function(){
		var nArtId = $('#art_id').val();
		var sLogin = $("#art_login").val();
		var sUsername = $("#art_username").val();
		var sArtStatus = $('#art_status').val();
		var nPerPage = $(this).val();
		var nOrder = $('#art_order').val();
		var sDirect = $('#art_direct').val();
		window.document.location.href = site_url + 'adm_user/listing/' + nPerPage + '/' + nOrder + '/' + sDirect + '/' + nArtId + '~' + sLogin + '~' + sUsername + '~' + sArtStatus + '/0';
		return false;
	});
	$(function() {
		var cache = {},
			lastXhr;
		$( "#art_login" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_user/autocomplete_login", request, function( data, status, xhr ) {
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
		$( "#art_username" ).autocomplete({
			minLength: 2,
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response( cache[ term ] );
					return;
				}

				lastXhr = $.getJSON( site_url + "adm_user/autocomplete_username", request, function( data, status, xhr ) {
					cache[ term ] = data;
					if ( xhr === lastXhr ) {
						response( data );
					}
				});
			}
		});
	});
});