var ajax_spa_add = function() {
		jQuery('body').append( '<div class="dialog_spa_add container_16" style="display:none;"></div>' );
		jQuery.get(site_url + 'adm_spas/spa_add', function(data) {
			jQuery('.dialog_spa_add').html(data);
			jQuery('.dialog_spa_add [type=submit]').click(function(){
				var title = jQuery('.dialog_spa_add [name=title]').val();
				var com_city_id = jQuery('.dialog_spa_add [name=com_city_id]').val();
				var com_order = jQuery('.dialog_spa_add [name=com_order]').val();
				var com_active = jQuery('.dialog_spa_add [name=com_active]').val();
				var com_contacts = jQuery('.dialog_spa_add [name=com_contacts]').val();
				var com_midseason_pay_type = jQuery('.dialog_spa_add [name=com_midseason_pay_type]').val();
				var com_reservation_email = jQuery('.dialog_spa_add [name=com_reservation_email]').val();
				var com_reservation_name = jQuery('.dialog_spa_add [name=com_reservation_name]').val();
				var com_reservation_email2 = jQuery('.dialog_spa_add [name=com_reservation_email2]').val();
				var com_reservation_name2 = jQuery('.dialog_spa_add [name=com_reservation_name2]').val();
				var com_facility_id = $('.dialog_spa_add [name="com_facility_id[]"]:checked').map(function(i,n) {
					return $(n).val();
				}).get();
				var com_medical_treatment_id = $('.dialog_spa_add [name="com_medical_treatment_id[]"]:checked').map(function(i,n) {
					return $(n).val();
				}).get();
				var com_essential_info_id = $('.dialog_spa_add [name="com_essential_info_id[]"]:checked').map(function(i,n) {
					return $(n).val();
				}).get();
				
				if ( title != '' && com_city_id != '' && com_active != '' && com_contacts != '' && com_midseason_pay_type != '' && com_reservation_email != '' && com_reservation_name != '' && (com_order == '' || parseInt(com_order) > 0)) {
					jQuery.post(site_url + 'adm_spas/spa_add', {title: title, com_city_id: com_city_id, com_order: com_order, com_active: com_active, com_contacts: com_contacts, com_midseason_pay_type: com_midseason_pay_type, com_reservation_email: com_reservation_email, com_reservation_name: com_reservation_name, com_reservation_email2: com_reservation_email2, com_reservation_name2: com_reservation_name2, 'com_essential_info_id[]': com_essential_info_id, 'com_medical_treatment_id[]': com_medical_treatment_id, 'com_facility_id[]': com_facility_id}, function(data) {		
						if ( parseInt(data.spa_id) ) {
							jQuery('.dialog_spa_add').dialog('destroy').remove();
							jQuery('#new_spa_id').append(jQuery('<option>', {value : data.spa_id}).text(data.title));
							jQuery('#new_spa_id :last').attr('selected', true);
							var nSpaId = data.spa_id;
							var oCityId = $('#new_city_id');
							var oCityTitle = $('#new_city_title');
							oCityTitle.addClass('ui-autocomplete-loading');
							$.post(site_url + 'adm_spas/get_spa_city', {id: nSpaId}, function(data){
								oCityTitle.removeClass('ui-autocomplete-loading');
								oCityId.val(data.id);
								oCityTitle.attr('disabled', false);
								oCityTitle.val(data.title);
								oCityTitle.attr('disabled', true);
							}, 'json')
							.error(function(){
								oCityTitle.removeClass('ui-autocomplete-loading');
								alert('Adding hotel spa was successfull! But we have error during searching a city of new hotel. Try change back and forth Hotel spa option of select area');
							});
						}
						else {
							alert(data.validation_errors);
						}
					}, 'json')
					.error(function(){
						alert('AJAX request error');
						jQuery('.dialog_spa_add').dialog('destroy').remove();
					});
				}
				return false;
			});
			jQuery('.dialog_spa_add form').submit(function() {
				return false;
			});
			jQuery('.dialog_spa_add').dialog({
				modal: true,
				height: 900,
				width: 1010,
				close: function(){
					jQuery('.dialog_spa_add').dialog('destroy').remove();
				}
			});
			jQuery('.dialog_spa_add .cancel').click(function() {
				jQuery('.dialog_spa_add').dialog('destroy').remove();
				return false;
			});
			
		});
		return false;
}