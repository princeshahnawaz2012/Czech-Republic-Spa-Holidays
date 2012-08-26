$(document).ready(function(){
	$('#sync_add').click(function(){
		var currencies = [];
		$('.exchange_rate_input').each(function(){
			$(this).addClass('ui-autocomplete-loading');
			currencies.push($(this).attr('id').substr(0, 7));
		});
		sync_remote(currencies);
		return false;
	});
	
	$('.sync').click(function(){
		var currency = $(this).attr('id').substr(0, 7);
		var currencies = [currency];
		$('#' + currency + '_exchange_rate').addClass('ui-autocomplete-loading');
		sync_remote(currencies);
		return false;
	});
});

var sync_remote = function(currencies) {
	$.post(site_url + 'adm_currencies/sync_remote/', {currencies: currencies}, function(json){
		$('.exchange_rate_input').removeClass('ui-autocomplete-loading');
		if (json.result == 0) {
			alert("Sync are failure.\nAJAX response:\n" + JSON.stringify(json));
		}
		else {
			for(var cur_key in json.rates) {
				if(json.rates.hasOwnProperty(cur_key)) {
					if (parseFloat(json.rates[cur_key]) != 0.0) {
						$('#' + cur_key + '_exchange_rate').val(json.rates[cur_key]).removeClass('ui-autocomplete-loading').addClass('ajax-success');
					}
					else {
						$('#' + cur_key + '_exchange_rate').val(json.rates[cur_key]).removeClass('ui-autocomplete-loading').addClass('ajax-error');
					}
				}
			}
			setTimeout(function(){
				$('.exchange_rate_input').removeClass('ajax-success').removeClass('ajax-error');
			}, 1000);
		}
	}, 'json')
	.error(function(){
		$('.exchange_rate_input').removeClass('ui-autocomplete-loading');
		alert("AJAX request error");
	});
}