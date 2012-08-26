{include file="regions/head.tpl"}
<body>
<div class="content" align="center" >
    <div id="all">
{$sErrorBlock}
    {include file="regions/header.tpl"}
	<div id="wrapper">
	{include file="regions/top.tpl"}
		<div class="sign_in">
	    {$content}
		</div>
	</div>
	{include file="regions/footer.tpl"}
    </div>
</div>
</body>
</html>