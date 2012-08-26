$(document).ready(function(){
	$('.save').click(function(){
		var input = $(this).prev();
		input.addClass('ui-autocomplete-loading');
		var id = parseInt(input.attr('id'));
		var order = parseInt(input.val());
		if(isNaN(order)){
			order = 0;
		}
		$.post(site_url + 'adm_programmes/image_order_save', {id: id, order: order}, function(data){
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
});