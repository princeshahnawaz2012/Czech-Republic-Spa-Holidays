var ajax_country_add = function() {
		jQuery('body').append( '<div class="dialog_country_add container_16" style="display:none;"></div>' );
		jQuery.get(site_url + 'adm_locations/country_add', function(data) {
			jQuery('.dialog_country_add').html(data);
			jQuery('.dialog_country_add [type=submit]').click(function(){
				var title = jQuery('.dialog_country_add [name=title]').val();
				var com_iso = jQuery('.dialog_country_add [name=com_iso]').val();
				var com_order = jQuery('.dialog_country_add [name=com_order]').val();
				if ( title != '' && com_iso != '' && (com_order == '' || parseInt(com_order) > 0) ) {
					jQuery.post(site_url + 'adm_locations/country_add', {title: title, com_iso: com_iso, com_order: com_order}, function(data) {		
						if ( parseInt(data.country_id) ) {
							jQuery('#new_country_title').append(jQuery('<option>', {value : data.country_id}).text(data.title));
							jQuery('#new_country_title :last').attr('selected', true);
							jQuery('.dialog_country_add').dialog('destroy').remove();
						}
						else {
							alert(data.validation_errors);
						}
					}, 'json')
					.error(function(){
						alert('AJAX request error');
						jQuery('.dialog_country_add').dialog('destroy').remove();
					});
				}
				return false;
			});
			jQuery('.dialog_country_add form').submit(function() {
				return false;
			});
			jQuery('.dialog_country_add').dialog({
				modal: true,
				height: 300,
				width: 1010,
				close: function(){
					jQuery('.dialog_country_add').dialog('destroy').remove();
				}
			});
			jQuery('.dialog_country_add .cancel').click(function() {
				jQuery('.dialog_country_add').dialog('destroy').remove();
				return false;
			});
			
		});
		return false;
}