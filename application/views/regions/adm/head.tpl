{if isset($sheadContent) && $sheadContent}
{$sheadContent}
{else}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="{$sMetaKeywords}" />
		<meta name="description" content="{$sMetaDescription}" />
		<title>{$sSiteTitle}</title>
		<link rel="icon" type="image/vnd.microsoft.icon" href="/adm_files/img/favicon_engine.ico" />
		
		<link rel="stylesheet" href="/adm_files/css/960.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="/adm_files/css/template.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="/adm_files/css/colour.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="/adm_files/css/jquery-ui-1.8.21.custom.css" type="text/css" media="screen" charset="utf-8" />
		<!--[if IE]><![if gte IE 6]><![endif]-->
		<script src="/adm_files/js/jquery-1.7.2.min.js" type="text/javascript"></script>
		<script src="/adm_files/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>
		<!--<script type="text/javascript">
			$(function() {
				$("#content .grid_5, #content .grid_6").sortable({
					placeholder: 'ui-state-highlight',
					forcePlaceholderSize: true,
					connectWith: '#content .grid_6, #content .grid_5',
					handle: 'h2',
					revert: true
				});
				$("#content .grid_5, #content .grid_6").disableSelection();
			});
		</script>-->
		<!--[if IE]><![endif]><![endif]-->		
		<script type="text/javascript">
			{foreach from=$aTemplateVar item=v key=k}
				var {$k}={$v};
			{/foreach}
		var site_url="{$site_url}";
		</script>
		{$sStyles}
		{$sScripts}
	</head>
{/if}