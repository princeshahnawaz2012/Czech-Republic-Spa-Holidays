<div id="container">
	<h1>{$sTitle}</h1>

	<div id="article_body">
		<ul>
		{foreach $aLinks as $aLink}
			<li><a href="{$aLink.link}">{$aLink.title}</a></li>
		{/foreach}
		</ul>
	</div>

</div>