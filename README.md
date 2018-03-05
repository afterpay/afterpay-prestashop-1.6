<h2> 1.1 Website Configuration </h2>
<p>
	Afterpay operates under a list of assumptions based on PrestaShop Back Office configurations. To align with these assumptions, the PrestaShop Back Office configurations must reflect the below:
</p>

<ol>
	<li> 
		Website Currency must be set to 'AUD'
		<ol>
			<li>PrestaShop Back Office > Localization > Currencies</li>
		</ol>
	</li>
	<li> 
		Website Country must be set to ‘AU’
		<ol>
			<li>PrestaShop Back Office > Localization > Countries</li>
		</ol>
	</li>
	<li> 
		'Zip/Costal Code' must be set to 'Yes'
		<ol>
			<li>PrestaShop Back Office > Localization > Countries > Australia</li>
		</ol>
	</li>
	<li> 
		'Contains States' must be set to 'Yes'
		<ol>
			<li>PrestaShop Back Office > Localization > Countries > Australia</li>
		</ol>
	</li>
	<li> 
		'Address' required fields must include the below values:
		<ol>
			<li>Firstname, lastname, email, address1, postcode, city, phone</li>
			<li>PrestaShop Back Office > Localization > Countries > Australia</li>
		</ol>
	</li>
</ol>


<h2> 1.2 New Afterpay Module Installation </h2>
<p>This section outlines the steps to install the Afterpay module on a PrestaShop website for the first time.</p>

<ol>
	<li> Download the Afterpay Module (<a href="https://github.com/afterpay/afterpay-prestashop-1.6/raw/master/afterpay.zip">https://github.com/afterpay/afterpay-prestashop-1.6/raw/master/afterpay.zip</a>). </li>
	<li> Navigate to: <em>PrestaShop Back Office > Modules > Modules & Services</em>.</li>
	<li> Click <em>Add a new module</em> </li>
	<li> Click <em>Choose a file</em> </li>
	<li> Select the Afterpay module ZIP file. </li>
	<li> Click <em>Upload this Module</em> </li>
	<li> Under the <em>Modules List</em> section, locate the Afterpay Payment Gateway module and click <em>install</em> </li>
	<li> Upon a successful installation, the Afterpay Payment Gateway configuration screen will be displayed with a notification stating: <em>Module(s) installed successfully</em>. </li>
</ol>

<h2> 1.3 Afterpay Merchant Setup </h2>
<p> To configure PrestaShop to utilise the Afterpay Payment Gateway, the below steps must be completed. </p>
<p> <em>Prerequisite for this section is to obtain an Afterpay Merchant ID and Merchant Key from Afterpay.</em> </p>

<ol>
	<li> 
		Upon completion of the installation, you will be redirected to the Afterpay module configuration screen, alternatively:
		<ul>
			<li>Navigate to: <em>PrestaShop Back Office > Modules > Modules & Services</em>.</li>
			<li>Locate the Afterpay Payment Gateway module and click 'Configure'.</li>
		</ul>
	</li>
	<li> Enable the Afterpay module by setting 'Enabled' to 'Yes'. </li>
	<li> Enter 'Merchant ID'. </li>
	<li> Enter 'Merchant Key'. </li>
	<li> 
		Select applicable 'API Environment'.
		<ul>
			<li>'Sandbox' API Environment for performing test transactions on a staging website.</li>
			<li>'Production' API Environment for live transactions on the production website.</li>
		</ul>
	</li>
	<li> Click 'Save'. </li>
	<li> Upon a successful configuration save, the Min and Max Payment Limit values will be updated with a notification stating: <em>Settings Updated</em>. </li>
</ol>

<h2> 1.4 Upgrade of Afterpay Module </h2>
<p> 
	This section outlines the steps to upgrade the currently installed Afterpay module. The process involves the complete removal of the currently installed module, followed by the installation of the new module.
</p>

<ol>
	<li>Navigate to: <em>PrestaShop Back Office > Modules > Modules & Services</em>.</li>
	<li>Locate the Afterpay Payment Gateway module.</li>
	<li>Click the dropdown and select 'Uninstall'.</li>
	<li>Click the dropdown and select 'Delete'.</li>
	<li> Download the Afterpay Module (<a href="https://github.com/afterpay/afterpay-prestashop-1.6/raw/master/afterpay.zip">https://github.com/afterpay/afterpay-prestashop-1.6/raw/master/afterpay.zip</a>). </li>
	<li> Navigate to: <em>PrestaShop Back Office > Modules > Modules & Services</em>.</li>
	<li> Click <em>Add a new module</em> </li>
	<li> Click <em>Choose a file</em> </li>
	<li> Select the Afterpay module ZIP file. </li>
	<li> Click <em>Upload this Module</em> </li>
	<li> Under the <em>Modules List</em> section, locate the Afterpay Payment Gateway module and click <em>install</em> </li>
	<li> Upon a successful installation, the Afterpay Payment Gateway configuration screen will be displayed with a notification stating: <em>Module(s) installed successfully</em>. </li>
	<li> Enable the Afterpay module by setting 'Enabled' to 'Yes'. </li>
	<li> Enter 'Merchant ID'. </li>
	<li> Enter 'Merchant Key'. </li>
	<li> 
		Select applicable 'API Environment'.
		<ul>
			<li>'Sandbox' API Environment for performing test transactions on a staging website.</li>
			<li>'Production' API Environment for live transactions on the production website.</li>
		</ul>
	</li>
	<li> Click 'Save'. </li>
	<li> Upon a successful configuration save, the Min and Max Payment Limit values will be updated with a notification stating: <em>Settings Updated</em>. </li>
</ol>