var prolong_editing = function(){
	var nArticleId = jQuery('#art_id').val();
	var sLang = jQuery('#art_lang').val();
	var url = '/adm_trans/article_prolong_editing/' + nArticleId + '/' + sLang;
	jQuery.ajax(url);
}

var nInterval = setInterval(prolong_editing, 66000);