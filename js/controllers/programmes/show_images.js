var toggle_more_photos = function(obj) {
	$('#more_photos').slideToggle(1000, function() {
		if($(obj).text() == str_hide_photos) {
			$(obj).text(str_show_more_photos);
		}
		else {
			$(obj).text(str_hide_photos);
		}
	});
}