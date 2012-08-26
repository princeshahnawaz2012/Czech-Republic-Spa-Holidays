$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '/js/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			skin : "o2k7",
			skin_variant : "silver",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,jbimages",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,fullscreen",
			theme_advanced_buttons2 : "styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor",
			theme_advanced_buttons3 : "undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,jbimages,visualchars,nonbreaking,template,pagebreak,|,cut,copy,paste,pastetext,pasteword",
			theme_advanced_buttons4 : "tablecontrols,|,hr,removeformat,visualaid,|,insertdate,inserttime,preview,|,print",
			theme_advanced_buttons5 : "sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,insertlayer,moveforward,movebackward,absolute,|,cite,abbr,acronym,del,ins,attribs,|,styleprops",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "/css/style.css",
			
			relative_urls : false,

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});