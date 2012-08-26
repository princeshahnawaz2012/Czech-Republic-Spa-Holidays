<div id="head"><a href="{$site_url}admin">Engine of Site | Czech Spa Holidays</a></div>

<ul id="navigation">
	<!--<li><span class="active">Overview</span></li>
	<li><a href="#">News</a></li>
	<li><a href="#">Users</a></li>-->
	{foreach $menu as $key => $value}
		<li>
			<a href="{$site_url}{$value.link}">{$value.name}</a>
			{if isset($value.sub_menu)}
				<ul>
				{foreach $value.sub_menu as $sub1}
					<li>
						<a href="{$site_url}{$sub1.link}">{$sub1.name}</a>
						{if isset($sub1.sub_menu)}
							<ul>
							{foreach $sub1.sub_menu as $sub2}
								<li>
									<a href="{$site_url}{$sub2.link}">{$sub2.name}</a>
								</li>
							{/foreach}
							</ul>
						{/if}
					</li>
				{/foreach}
				</ul>
			{/if}
		</li>
	{/foreach}					
	{if isset($nUserId)}<li><a href="{$site_url}admin/logout">Logout</a></li>{/if}
</ul>