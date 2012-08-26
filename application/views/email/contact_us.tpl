<html>
	<body style="color: black; font-family: Verdana; font-size: 11pt;">
		<table style="border: 2px solid green; border-collapse: collapse; border-spacing: 0px; width: 620px; margin: 0px auto;" align="center">
			<tr>
				<td style="border-bottom: 1px solid green; text-align: right; padding: 15px 5px;">
					<h1 style="font-size: 12pt; margin: 0px; padding-bottom: 15px;">
						{$sEnquiry}: {$sTypeValue}
					</h1>
					<span style="font-size: 10pt;">
						{vlang('Date')}: {$sDateValue}
					</span>
				</td>
			</tr>
			<tr>
				<td style="text-align: left; padding: 3px 5px 80px 5px;">
					{vlang('Dear  ', $aNameValue)}
					<br />
					<br />
					{vlang('Thank you for your enquiry below. A member of our team will get back to you as soon as possible.')}
					<br />
					<br />
					=========================
					<br />
					<br />
					<span style="color: rgb(15, 36, 62);">
						{$sFullName}: {$aNameValue[0]}
						<br />
						{$sEmail}: {$sEmailValue}
						<br />
						{$sTelephone}: {$sTelephoneValue}
						<br />
						<br />
						{$sEnquiry}: {$sEnquiryValue}
					</span>
				</td>
			</tr>
			</tr>
				<td style="text-align: center; padding: 20px 0px 15px; color: rgb(128, 128, 128); font-size: 10pt;">
					<b>{vlang('Organization title')}</b>
					<br />
					<span>{vlang('Organization address')}</span>
					<br />
					<span>{vlang('Organization telephone')}</span>
					<br />
					<br />
					<span>{vlang('Organization registered')}</span>
				</td>
			</tr>
		</table>
	</body>
</html>