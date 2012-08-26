$(function() {
	$('#new_com_date_from').datepicker({
		dateFormat: "yy-mm-dd",
		onSelect: function(dateText, inst) {
			$('#new_com_date_till').datepicker("option", "minDate", new Date(dateText));
		}
	});
	$('#new_com_date_till').datepicker({
		dateFormat: "yy-mm-dd",
		onSelect: function(dateText, inst) {
			$('#new_com_date_from').datepicker("option", "maxDate", new Date(dateText));
		}
	});
	if($('#new_com_date_from').val() != '') {
		$('#new_com_date_till').datepicker("option", "minDate", new Date($('#new_com_date_from').val()));
	}
	if($('#new_com_date_till').val() != '') {
		$('#new_com_date_from').datepicker("option", "maxDate", new Date($('#new_com_date_till').val()));
	}
});