$(document).ready(function(){
	
	//---------Upload map------------//
	initOnClickUploadButton('picture')
	//-------End of Upload map-----//
});

/**
 * 
 */
var initOnClickUploadButton = function(image_type) {
	var temp_name = $('#temp_name_' + image_type).val();
	$('#button_' + image_type + '_upload').upload({
			name: 'image_file',
			action: site_url + image_upload_url_begin + image_type,
			enctype: 'multipart/form-data',
			params: {temp_name: temp_name},
			autoSubmit: true,
			onSubmit: function() {
				$('#' + image_type + '_image_block .panel').addClass('ui-autocomplete-loading');
			},
			onComplete: function(responseText) {
				$('#' + image_type + '_image_block .panel').removeClass('ui-autocomplete-loading');
				var json = eval('(' + responseText + ')');
				if ( json.result == NaN || json.result != 'ok') {
					alert("Uploading failed\nResponse: " + responseText);
				}
				else {
					$('#new_com_' + image_type + '_ext').val(json.ext);
//					var max_uploaded_image_width = $('#' + image_type + '_image_block .image_block').css('width');
					$('#' + image_type + '_image_block .image_block').html('<img src="/' + temp_dir + temp_name + '.' + json.ext + '?p=' + Math.random() + '" alt="' + image_type + '" />');
					var img = $('#' + image_type + '_image_block .image_block img');
					var x = 0;
					var y = 0;
					var w = 0;
					var h = 0;
					$("<img/>") // Make in memory copy of image to avoid css issues
						.attr("src", $(img).attr("src"))
						.load(function() {
							w = this.width;   // Note: $(this).width() will not
							h = this.height; // work for in memory images.
							$('#' + image_type + '_image_block  .crop_w').text(w);
							$('#' + image_type + '_image_block  .crop_h').text(h);
							$('#' + image_type + '_image_block  .crop_x').text(x);
							$('#' + image_type + '_image_block  .crop_y').text(y);
						});					
					$('#' + image_type + '_image_block  .crop_w').text(w);
					$('#' + image_type + '_image_block  .crop_h').text(h);
					$('#' + image_type + '_image_block  .crop_x').text(x);
					$('#' + image_type + '_image_block  .crop_y').text(y);
					$('#' + image_type + '_image_block .panel .manage').removeClass('hide');
					$('#' + image_type + '_image_block .panel .states').removeClass('hide');
					initUploadedImage(image_type);
					initButtonCrop(image_type);
					initButtonDelete(image_type);
					initButtonResize(image_type);
					initButtonRotate(image_type);
				}
			},
			onSelect: function() {}
	});
}

var initUploadedImage = function (image_type) {
	$('#' + image_type + '_image_block  .image_block img').Jcrop({
		onChange:    function(coords){
			$('#' + image_type + '_image_block  .crop_w').text(coords.w);
			$('#' + image_type + '_image_block  .crop_h').text(coords.h);
			$('#' + image_type + '_image_block  .crop_x').text(coords.x);
			$('#' + image_type + '_image_block  .crop_y').text(coords.y);
		},
		setSelect: [0,0,min_programme_image_width,min_programme_image_height],
		bgColor:     'black',
		bgOpacity:   .9,
		minSize: [min_programme_image_width, min_programme_image_height]
	});
}

var initButtonCrop = function (image_type) {
	var temp_name = $('#temp_name_' + image_type).val();
	$('#' + image_type + '_image_block .panel .crop_image').click(function(){
		var width = $('#' + image_type + '_image_block .crop_w').text();
		var height = $('#' + image_type + '_image_block .crop_h').text();
		var x_axis = $('#' + image_type + '_image_block .crop_x').text();
		var y_axis = $('#' + image_type + '_image_block .crop_y').text();
		var ext = $('#new_com_' + image_type + '_ext').val();
		if ( width == '' || height == '' || x_axis == '' || y_axis == '' ) {
			alert("Select an area of the image for cropping.");
		}
		else {
			$('#' + image_type + '_image_block .panel').addClass('ui-autocomplete-loading');
			$.post(site_url + image_crop_url_begin + image_type, {width: width, height: height, x_axis: x_axis, y_axis: y_axis, ext: ext, temp_name: temp_name}, function(crop_json){
				$('#' + image_type + '_image_block .panel').removeClass('ui-autocomplete-loading');
				if ( crop_json.result != 'ok' ) {
					alert("Remote error during cropping!\nResponse: " + JSON.stringify(crop_json));
				} else {
					$('#' + image_type + '_image_block .crop_w').text('');
					$('#' + image_type + '_image_block .crop_h').text('');
					$('#' + image_type + '_image_block .crop_x').text('');
					$('#' + image_type + '_image_block .crop_y').text('');
					$('#' + image_type + '_image_block .image_block').html('<img src="/' + temp_dir + temp_name + '.' + ext + '?p=' + Math.random() + '" alt="' + image_type + '" />');
					initUploadedImage(image_type);
				}
			}, 'json')
			.error(function(){
				$('#' + image_type + '_image_block .panel').removeClass('ui-autocomplete-loading');
				alert("AJAX request error!");
			});
		}
		return false;
	});
}


var initButtonDelete = function(image_type) {
	$('#' + image_type + '_image_block .panel .delete_image').click(function(){
		if (confirm("Are you sure?")) {
			$('#new_com_' + image_type + '_ext').val('');
			$('#' + image_type + '_image_block .panel .crop_w').text('');
			$('#' + image_type + '_image_block .panel .crop_h').text('');
			$('#' + image_type + '_image_block .panel .crop_x').text('');
			$('#' + image_type + '_image_block .panel .crop_y').text('');
			$('#' + image_type + '_image_block .image_block').html('');
			$('#' + image_type + '_image_block .panel .manage').addClass('hide');
			$('#' + image_type + '_image_block .panel .states').addClass('hide');
		}
		return false;
	});
}

var initButtonResize = function (image_type) {
	var temp_name = $('#temp_name_' + image_type).val();
	$('#' + image_type + '_image_block .panel .resize_image').click(function(){
		var width = $('#' + image_type + '_image_block .crop_w').text();
		var height = $('#' + image_type + '_image_block .crop_h').text();
		var ext = $('#new_com_' + image_type + '_ext').val();
		if ( width == '' || height == '' ) {
			alert("Select an area of the image for resizing.");
		}
		else {
			$('#' + image_type + '_image_block .panel').addClass('ui-autocomplete-loading');
			$.post(site_url + image_resize_url_begin + image_type, {width: width, height: height, ext: ext, temp_name: temp_name}, function(crop_json){
				$('#' + image_type + '_image_block .panel').removeClass('ui-autocomplete-loading');
				if ( crop_json.result != 'ok' ) {
					alert("Remote error during resizing!\nResponse: " + JSON.stringify(crop_json));
				} else {
					$('#' + image_type + '_image_block .crop_w').text('');
					$('#' + image_type + '_image_block .crop_h').text('');
					$('#' + image_type + '_image_block .crop_x').text('');
					$('#' + image_type + '_image_block .crop_y').text('');
					$('#' + image_type + '_image_block .image_block').html('<img src="/' + temp_dir + temp_name + '.' + ext + '?p=' + Math.random() + '" alt="' + image_type + '" />');
					initUploadedImage(image_type);
				}
			}, 'json')
			.error(function(){
				$('#' + image_type + '_image_block .panel').removeClass('ui-autocomplete-loading');
				alert("AJAX request error!");
			});
		}
		return false;
	});
}

var initButtonRotate = function (image_type) {
	var temp_name = $('#temp_name_' + image_type).val();
	$('#' + image_type + '_image_block .panel .rotate_image').click(function(){
		var angle = 90;
		var ext = $('#new_com_' + image_type + '_ext').val();
		if ( angle == 0 ) {
			alert("Angle of rotation must be great than 0.");
		}
		else {
			$('#' + image_type + '_image_block .panel').addClass('ui-autocomplete-loading');
			$.post(site_url + image_rotate_url_begin + image_type, {angle: angle, ext: ext, temp_name: temp_name}, function(crop_json){
				$('#' + image_type + '_image_block .panel').removeClass('ui-autocomplete-loading');
				if ( crop_json.result != 'ok' ) {
					alert("Remote error during rotating!\nResponse: " + JSON.stringify(crop_json));
				} else {
					$('#' + image_type + '_image_block .crop_w').text('');
					$('#' + image_type + '_image_block .crop_h').text('');
					$('#' + image_type + '_image_block .crop_x').text('');
					$('#' + image_type + '_image_block .crop_y').text('');
					$('#' + image_type + '_image_block .image_block').html('<img src="/' + temp_dir + temp_name + '.' + ext + '?p=' + Math.random() + '" alt="' + image_type + '" />');
					initUploadedImage(image_type);
				}
			}, 'json')
			.error(function(){
				$('#' + image_type + '_image_block .panel').removeClass('ui-autocomplete-loading');
				alert("AJAX request error!");
			});
		}
		return false;
	});
}