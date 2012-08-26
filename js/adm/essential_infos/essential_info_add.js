var ajax_essential_info_add = function() {
		jQuery('body').append( '<div class="dialog_essential_info_add container_16" style="display:none;"></div>' );
		jQuery.get(site_url + 'adm_essential_infos/essential_info_add', function(data) {
			jQuery('.dialog_essential_info_add').html(data);
			jQuery('.dialog_essential_info_add [type=submit]').click(function(){
				var title = jQuery('.dialog_essential_info_add [name=title]').val();
				var com_order = jQuery('.dialog_essential_info_add [name=com_order]').val();
				var short_desc = jQuery('.dialog_essential_info_add [name=short_desc]').val();
				var com_active = jQuery('.dialog_essential_info_add [name=com_active]').val();
				if ( title != '' && com_active != '' ) {
					jQuery.post(site_url + 'adm_essential_infos/essential_info_add', {title: title, com_order: com_order, short_desc: short_desc, com_active: com_active}, function(data) {		
						if ( parseInt(data.essential_info_id) ) {
//							jQuery('#new_essential_info_title').append(jQuery('<option>', { value : data.essential_info_id }).text(data.title));
//							jQuery('#new_essential_info_title :last').attr('selected', true);
							
							jQuery('#essential_infos div.master_list').append('<label><input type="checkbox" name="com_essential_info_id[]" value="' + data.essential_info_id + '" checked="checked" />' + data.title + '</label><br />');
							jQuery('.dialog_essential_info_add').dialog('destroy').remove();
						}
						else {
							alert(data.validation_errors);
						}						
					}, 'json')
					.error(function(){
						alert('AJAX request error');
						jQuery('.dialog_essential_info_add').dialog('destroy').remove();
					});
				}
				return false;
			});
			jQuery('.dialog_essential_info_add form').submit(function() {
				return false;
			});
			jQuery('.dialog_essential_info_add').dialog({
				modal: true,
				height: 400,
				width: 1010,
				close: function(){
					jQuery('.dialog_essential_info_add').dialog('destroy').remove();
				}
			});
			jQuery('.dialog_essential_info_add .cancel').click(function() {
				jQuery('.dialog_essential_info_add').dialog('destroy').remove();
				return false;
			});
		});		
		return false;
}