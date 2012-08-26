var ajax_region_add = function() {
		jQuery('body').append( '<div class="dialog_region_add container_16" style="display:none;"></div>' );
		jQuery.get(site_url + 'adm_locations/region_add', function(data) {
			jQuery('.dialog_region_add').html(data);
			jQuery('.dialog_region_add [type=submit]').click(function(){
				var title = jQuery('.dialog_region_add [name=title]').val();
				var country_id = jQuery('.dialog_region_add [name=com_country_id]').val();
				var com_order = jQuery('.dialog_region_add [name=com_order]').val();
				if ( title != '' && country_id != '' && (com_order == '' || parseInt(com_order) > 0) ) {
					jQuery.post(site_url + 'adm_locations/region_add', {title: title, com_country_id: country_id, com_order: com_order}, function(data) {		
						if ( parseInt(data.region_id) ) {
							jQuery('#new_region_title').append(jQuery('<option>', {value : data.region_id}).text(data.title));
							jQuery('#new_region_title :last').attr('selected', true);
							jQuery('.dialog_region_add').dialog('destroy').remove();
						}
						else {
							alert(data.validation_errors);
						}
					}, 'json')
					.error(function(){
						alert('AJAX request error');
						jQuery('.dialog_region_add').dialog('destroy').remove();
					});
				}
				return false;
			});
			jQuery('.dialog_region_add form').submit(function() {
				return false;
			});
			jQuery('.dialog_region_add').dialog({
				modal: true,
				height: 300,
				width: 1010,
				close: function(){
					jQuery('.dialog_region_add').dialog('destroy').remove();
				}
			});
			jQuery('.dialog_region_add .cancel').click(function() {
				jQuery('.dialog_region_add').dialog('destroy').remove();
				return false;
			});
			
		});
		return false;
}