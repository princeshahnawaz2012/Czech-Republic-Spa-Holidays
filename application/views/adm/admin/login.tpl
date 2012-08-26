<h1>{$sSiteTitle}</h1>
<div class="grid_6">&nbsp;</div>
<div class="grid_4">
	{form_open('admin/login')}
		<fieldset>
			<div class="field-holder">
				<label>{vlang('Login')} <small>{vlang('Required')}</small></label>
				<input type="text" name="login" value="{set_value('login')}" autocomplete="off" tabindex="1" />
			</div>
			<div class="field-holder">
				<label>{vlang('Password')} <small>{vlang('Required')}</small></label>
				<input type="password" name="pass" value="" autocomplete="off" tabindex="2" />
				<!--<p><a href="{$site_url}user/forgot">Forgot password</a></p>-->
			</div>
			<div class="go" align="center">
				<input type="submit" value="{vlang('Login')}" />
			</div>
		</fieldset>
	</form>
</div>