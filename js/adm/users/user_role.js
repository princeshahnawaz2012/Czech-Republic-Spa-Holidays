$(document).ready(function(){  
	$('select#role').change(function () {
		$('#manager').hide();
		$('#tranlator').hide();
		if( $('select#role').val() == '10' ) $('#manager').show();
		else if ( $('select#role').val() == '15' ) $('#tranlator').show();
	} );
		
	$('#manager').hide();
	$('#tranlator').hide();
	if( $('select#role').val() == '10' ) $('#manager').show();
	else if ( $('select#role').val() == '15' ) $('#tranlator').show();		
});