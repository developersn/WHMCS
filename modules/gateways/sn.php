<?php

	function sn_config() {
		$configarray = array("FriendlyName" => array("Type" => "System", "Value" => "پرداخت آنلاين کارت هاي شتاب"),
		 "merchantID" => array("FriendlyName" => "merchantID", "Type" => "text", "Size" => "50",),
		  "Currencies" => array("FriendlyName" => "Currencies", "Type" => "dropdown", "Options" => "Rial,Toman",),
		   "WebService" => array("FriendlyName" => "آيتم هاي اختياري وب سرويس", "Type" => "yesno", "Description" => "در صورت فعال بودن ، اطلاعات خریدار در پنل کاربری ثبت خواهد شد",),
		   );
		return $configarray;
	}
	function sn_link($params) {
# Gateway Specific Variables
		$merchantID = $params['merchantID'];
		$WebService = $params['WebService'];
		$currencies = $params['Currencies'];
# Invoice Variables
		$invoiceid = $params['invoiceid'];
		$description = $params["description"];
		$amount = $params['amount'];
		$amount = $amount;
		$currency = $params['currency'];
# Client Variables
		$firstname = $params['clientdetails']['firstname'];
		$lastname = $params['clientdetails']['lastname'];
		$email = $params['clientdetails']['email'];
		$address1 = $params['clientdetails']['address1'];
		$address2 = $params['clientdetails']['address2'];
		$city = $params['clientdetails']['city'];
		$state = $params['clientdetails']['state'];
		$postcode = $params['clientdetails']['postcode'];
		$country = $params['clientdetails']['country'];
		$phone = $params['clientdetails']['phonenumber'];
# System Variables
		$companyname = $params['companyname'];
		$systemurl = $params['systemurl'];
		$currency = $params['currency'];
# Enter your code submit to the gateway...
		$code = '
			<form method="post" action="./sn.php">
			<input type="hidden" name="merchantID" value="' . $merchantID . '" />
			<input type="hidden" name="WebService" value="' . $WebService . '" />
			<input type="hidden" name="invoiceid" value="' . $invoiceid . '" />
			<input type="hidden" name="amount" value="' . $amount . '" />
			<input type="hidden" name="currencies" value="' . $currencies . '" />
			<input type="hidden" name="systemurl" value="' . $systemurl . '" />
			<input type="hidden" name="description" value="' . $description . '" />
			<input type="hidden" name="Paymenter" value="' . $firstname .' '. $lastname . '" />
			<input type="hidden" name="Email" value="' . $email . '" />
			<input type="hidden" name="Mobile" value="' . $phone . '" />
			<input type="submit" name="pay" value=" پرداخت " />
			</form>';
		return $code;
	}
?>