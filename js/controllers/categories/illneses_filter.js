$(document).ready(function(){
	$('#category_filter input[type=checkbox]').change(function(){
		$.scrollTo('#category_filter', 600);
		var aIllnesesId = Array();
		var oCheckboxes = $('#category_filter input[type=checkbox]:checked');
		oCheckboxes.each(function(nIndex, oIllnese){
			aIllnesesId.push($(oIllnese).val());
		});
		var oLoadingIndicator = $('#ajax_filtering');
		oLoadingIndicator.css('width', $('#category_filter').parent('td').css('width'));
		oLoadingIndicator.css('height', $('#category_filter').parent('td').css('height'));
		oLoadingIndicator.show();
		$.post(site_url + 'programmes/illneses_filter/', {aIllnesesId: aIllnesesId, nCategoryId: nCategoryId}, function(sResponse){
			$('.big_table tbody').html(sResponse);
			oLoadingIndicator.hide();
		}, 'text')
		.error(function(){
			oLoadingIndicator.hide();			
		});
	});
	
	$('#reset_category_filter').click(function(){
		$.scrollTo('#category_filter', 1000);
		var oCheckboxes = $('#category_filter input[type=checkbox]:checked');
		if(oCheckboxes.length == 0){
			return false;
		}
		else{
			oCheckboxes.each(function(nIndex, oIllnese){
				$(oIllnese).attr('checked', false);
			});
		}
		var aIllnesesId = Array();
		var oLoadingIndicator = $('#ajax_filtering');
		oLoadingIndicator.css('width', $('#category_filter').parent('td').css('width'));
		oLoadingIndicator.css('height', $('#category_filter').parent('td').css('height'));
		oLoadingIndicator.show();
		$.post(site_url + 'programmes/illneses_filter/', {aIllnesesId: aIllnesesId, nCategoryId: nCategoryId}, function(sResponse){
			$('.big_table tbody').html(sResponse);
			oLoadingIndicator.hide();
		}, 'text')
		.error(function(){
			oLoadingIndicator.hide();			
		});
		return false;
	});
});