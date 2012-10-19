$(document).ready(function(){
	$('#category_filter input[type=checkbox]').change(function(){
		var sIllnese = $(this).next('label').text();
		var aProgrammes = $('.big_table tbody td.tb_h_2 a');
		aProgrammes.each(function(nIndex, oDesc){
			if(!have_illnese(sIllnese, oDesc.text())){
				
			}
		});
	});
});

var have_illnese = function(sIllnese, sDesc){
	return sDesc.indexOf(sIllnese) + 1;
}

var hide_tr = function(oDesc){
	oDesc.parent().parent().hide();
}

var show_tr = function(oDesc){
	oDesc.parent().parent().show();
}