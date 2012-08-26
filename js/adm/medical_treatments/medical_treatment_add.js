var ajax_medical_treatment_add = function() {
		jQuery('body').append( '<div class="dialog_medical_treatment_add container_16" style="display:none;"></div>' );
		jQuery.get(site_url + 'adm_medical_treatments/medical_treatment_add', function(data) {
			jQuery('.dialog_medical_treatment_add').html(data);
			jQuery('.dialog_medical_treatment_add [type=submit]').click(function(){
				var title = jQuery('.dialog_medical_treatment_add [name=title]').val();
				var com_order = jQuery('.dialog_medical_treatment_add [name=com_order]').val();
				var short_desc = jQuery('.dialog_medical_treatment_add [name=short_desc]').val();
				var com_active = jQuery('.dialog_medical_treatment_add [name=com_active]').val();
				if ( title != '' && com_active != '' ) {
					jQuery.post(site_url + 'adm_medical_treatments/medical_treatment_add', {title: title, com_order: com_order, short_desc: short_desc, com_active: com_active}, function(data) {		
						if ( parseInt(data.medical_treatment_id) ) {
//							jQuery('#new_medical_treatment_title').append(jQuery('<option>', { value : data.medical_treatment_id }).text(data.title));
//							jQuery('#new_medical_treatment_title :last').attr('selected', true);
							
							jQuery('#medical_treatments div.master_list').append('<label><input type="checkbox" name="com_medical_treatment_id[]" value="' + data.medical_treatment_id + '" checked="checked" />' + data.title + '</label><br />');
							jQuery('.dialog_medical_treatment_add').dialog('destroy').remove();
						}
						else {
							alert(data.validation_errors);
						}						
					}, 'json')
					.error(function(){
						alert('AJAX request error');
						jQuery('.dialog_medical_treatment_add').dialog('destroy').remove();
					});
				}
				return false;
			});
			jQuery('.dialog_medical_treatment_add form').submit(function() {
				return false;
			});
			jQuery('.dialog_medical_treatment_add').dialog({
				modal: true,
				height: 400,
				width: 1010,
				close: function(){
					jQuery('.dialog_medical_treatment_add').dialog('destroy').remove();
				}
			});
			jQuery('.dialog_medical_treatment_add .cancel').click(function() {
				jQuery('.dialog_medical_treatment_add').dialog('destroy').remove();
				return false;
			});
		});		
		return false;
}