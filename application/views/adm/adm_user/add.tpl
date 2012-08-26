<h1>{$sSiteTitle}</h1>
<div class="form-holder">
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save user')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_4">
			<label>Login <small>{vlang('Required')}</small></label>
			<input type="text" name="login" value="{set_value('login')}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>Full name <small>{vlang('Required')}</small></label>
			<input type="text" name="fio" value="{set_value('fio')}" autocomplete="off" />
		</div>	
		<div class="grid_4">
			<label>Email <small>{vlang('Required')}</small></label>
			<input type="text" name="email" value="{set_value('email')}" autocomplete="off" />
		</div>			
		<div class="grid_4">
			<label>Role <small>{vlang('Required')}</small></label>
			{html_options id="role" name=role options=$aData.role selected=set_value('role',0)}
		</div>
		<div class="grid_8">
			<label>Password <small>{vlang('Required')}</small></label>
			<input type="password" name="pass" value=""/>
		</div>	
		<div class="grid_8">
			<label>Password Confirmation <small>{vlang('Required')}</small></label>
			<input type="password" name="passconf" value=""/>
		</div>
		<div id="manager" class="hide grid_16">
			<label>Manager permissions</label>
			<div class="master_list">
				{html_checkboxes name="perm" options=$aData.menu_perm selected=$aData.perm_select separator="<br />"}
			</div>
		</div>
		<div id="tranlator" class="hide grid_16">
			<label>Translator permissions</label>
			<div class="master_list">
				{html_checkboxes name="lang" options=$aData.lang selected=$aData.lang_select separator="<br />"}
			</div>
		</div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save user')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}