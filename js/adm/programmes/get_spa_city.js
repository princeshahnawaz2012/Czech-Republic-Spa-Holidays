$(function(){
	$('#new_spa_id').change(function(){
		var nSpaId = $('#new_spa_id').val();
		var oCityId = $('#new_city_id');
		var oCityTitle = $('#new_city_title');
		oCityTitle.addClass('ui-autocomplete-loading');
		$.post(site_url + 'adm_spas/get_spa_city', {id: nSpaId}, function(data){
			oCityTitle.removeClass('ui-autocomplete-loading');
			oCityId.val(data.id);
			oCityTitle.attr('disabled', false);
			oCityTitle.val(data.title);
			oCityTitle.attr('disabled', true);
		}, 'json')
		.error(function(){
			oCityTitle.removeClass('ui-autocomplete-loading');
			alert('AJAX response error');
		});
	});
});