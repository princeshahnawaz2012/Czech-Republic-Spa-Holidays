			<div class="form-holder login">
				{form_open('adm_user/login')}
					<fieldset>
						<div class="field-holder">
							<label>Login <span>(required)</span></label>
							<input type="text" name="login" value="{set_value('login')}" />
						</div>
						<div class="field-holder">
							<label>Password <span>(required)</span></label>
							<input type="password" name="pass" value=""/>
							<!--<p><a href="{$site_url}user/forgot">Forgot password</a></p>-->
						</div>
						<div class="go">
							<input type="submit" value="Login" />
						</div>
					</fieldset>
				</form>
			</div>