var ajax_city_add = function() {
		jQuery('body').append( '<div class="dialog_city_add container_16" style="display:none;"></div>' );
		jQuery.get(site_url + 'adm_locations/city_add', function(data) {
			jQuery('.dialog_city_add').html(data);
			jQuery('.dialog_city_add [type=submit]').click(function(){
				var title = jQuery('.dialog_city_add [name=title]').val();
				var region_id = jQuery('.dialog_city_add [name=com_region_id]').val();
				var order = jQuery('.dialog_city_add [name=com_order]').val();
				var desc = jQuery('.dialog_city_add [name=desc]').val();
				var flag_label = jQuery('.dialog_city_add [name=flag_label]').val();
				var emblem_label = jQuery('.dialog_city_add [name=emblem_label]').val();
				var com_map_ext = jQuery('.dialog_city_add [name=com_map_ext]').val();
				var com_flag_ext = jQuery('.dialog_city_add [name=com_flag_ext]').val();
				var com_emblem_ext = jQuery('.dialog_city_add [name=com_emblem_ext]').val();
				if ( title != '' && region_id != '' && (order == '' || parseInt(order) > 0) ) {
					jQuery.post(site_url + 'adm_locations/city_add', {title: title, com_region_id: region_id, com_order: order, desc: desc, flag_label: flag_label, emblem_label: emblem_label, com_map_ext: com_map_ext, com_flag_ext: com_flag_ext, com_emblem_ext: com_emblem_ext}, function(data) {		
						if ( parseInt(data.city_id) ) {
							jQuery('#new_city_title').append(jQuery('<option>', { value : data.city_id }).text(data.title));
							jQuery('#new_city_title :last').attr('selected', true);
							jQuery('.dialog_city_add').dialog('destroy').remove();
						}
						else {
							alert(data.validation_errors);
						}						
					}, 'json')
					.error(function(){
						alert('AJAX request error');
						jQuery('.dialog_city_add').dialog('destroy').remove();
					});
				}
				return false;
			});
			jQuery('.dialog_city_add form').submit(function() {
				return false;
			});
			jQuery('.dialog_city_add').dialog({
				modal: true,
				height: 900,
				width: 1010,
				close: function(){
					jQuery('.dialog_city_add').dialog('destroy').remove();
				}
			});
			jQuery('.dialog_city_add .cancel').click(function() {
				jQuery('.dialog_city_add').dialog('destroy').remove();
				return false;
			});
			
			//---------Upload map------------//
			initOnClickUploadButton('map');
			//-------End of Upload map-----//
			//
			//---------Upload emblem------------//
			initOnClickUploadButton('emblem');
			//-------End of Upload emblem-----//
			//
			//---------Upload flag------------//
			initOnClickUploadButton('flag');
			//-------End of Upload flag-----//
			
		});		
		return false;
}