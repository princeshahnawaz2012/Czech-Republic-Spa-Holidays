{if isset($sheadContent) && $sheadContent}
{$sheadContent}
{else}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="{$sMetaKeywords}" />
<meta name="description" content="{$sMetaDescription}"/>
<title>{$sSiteTitle}</title>
<link rel="icon" type="image/vnd.microsoft.icon" href="/images/favicon.ico" />
<script type="text/javascript">
    {foreach from=$aTemplateVar item=v key=k}
        var {$k}={$v};
    {/foreach}
var site_url="{$site_url}";
</script>
<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="/css/fontface.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="/css/ui-lightness/jquery-ui-1.8.20.custom.css" type="text/css" media="screen" charset="utf-8" />
<script src="/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.11.custom.min.js" type="text/javascript"></script>
{$sStyles}
{$sScripts}
</head>
{/if}