$(document).ready(function(){
	var image_type = 'emblem';
	var temp_name = $('#temp_name_' + image_type).val();
	var ext = $('#new_com_' + image_type + '_ext').val();
	$('#' + image_type + '_image_block .image_block').html('<img src="/' + temp_dir + temp_name + '.' + ext + '?p=' + Math.random() + '" alt="' + image_type + '" />');
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
});