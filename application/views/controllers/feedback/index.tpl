<div id="container">
	<h1>{$sArticleTitle}</h1>

	<div id="article_body">
		{$sArticleFull}
	</div>

	<table id="feedback_form" width="100%">
		<tr>
			<td class="form-holder" width="66%" valign="top">
				{form_open()}
				<p>* - {vlang('field required')}</p>
				<p>{$sTitle} *: <input type="text" name="title" value="{set_value('title')}" /></p>
				<p>{$sFullName} *: <input type="text" name="name" value="{set_value('name')}" /></p>
				<p>{$sEmail} *: <input type="text" name="email" value="{set_value('email')}" /></p>
				<p>{$sTelephone} *: <input type="text" name="phone" value="{set_value('phone')}" /></p>
				<p class="feedback_type">
					<input type="radio" name="type" value="1" id="type_1" {set_radio('type', 1, TRUE)} /><label for="type_1">{vlang('General Enquiry')}</label>
					<br />
					<input type="radio" name="type" value="2" id="type_2" {set_radio('type', 2)} /><label for="type_2">{vlang('Cancel or Amend Booking')}</label>
					<br />
					<input type="radio" name="type" value="3" id="type_3" {set_radio('type', 3)} /><label for="type_3">{vlang('Programmes for Kids')}</label>
					<br />
					<input type="radio" name="type" value="4" id="type_4" {set_radio('type', 4)} /><label for="type_4">{vlang('Feedback')}</label>
				</p>
				<p><input type="checkbox" name="send_copy" value="1" id="copy" {set_checkbox('send_copy', '1')} /><label for="copy">{vlang('Send a copy to myself.')}</label></p>
				<p>{$sEnquiry} *:<br /><textarea name="enquiry" class="small_textarea">{set_value('enquiry')}</textarea></p>
				<p><input type="submit" name="feedback_submit" value="{vlang('Send Enquiry')}" /></p>
				{form_close()}
			</td>
			<td class="address" valign="top">
				{$sArticleFull2}
			</td>
		</tr>
	</table>

</div>