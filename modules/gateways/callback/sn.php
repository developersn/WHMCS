<?php
session_start();
	include ("../../../init.php");
	include ("../../../dbconnect.php");
	include ("../../../includes/functions.php");
	include ("../../../includes/gatewayfunctions.php");
	include ("../../../includes/invoicefunctions.php");
	$gatewaymodule = "sn";
	$GATEWAY = getGatewayVariables($gatewaymodule);
	if (!$GATEWAY["type"])
		die("Module Not Activated");

					// Security
					$sec=$_GET['sec'];
					$mdback = md5($sec.'vm');
					$mdurl=$_GET['md'];

					// Security
	if(!empty($_GET['sec']) AND !empty($_GET['md']) AND !empty($_GET['au']) AND $mdback == $mdurl){
					$transData = $_SESSION[$sec];
					$au=$transData['au']; //	
				    $invoiceid=$transData['order_id']; //
                    $amount=$transData['price']; //			
					$amountorg=$transData['amount']; //								

	$invoiceid = checkCbInvoiceID($invoiceid, $GATEWAY["name"]);

		$bank_return = $_POST + $_GET ;
		$data_string = json_encode(array (
		'pin' => $GATEWAY['merchantID'],
		'price' => $amount,
		'order_id' => $invoiceid,
		'au' => $au,
		'bank_return' =>$bank_return,
		));

		$ch = curl_init('https://developerapi.net/api/v1/verify');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
		);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		$result = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($result,true);


	if( ! empty($json['result']) and $json['result'] == 1){
	
		  addInvoicePayment($invoiceid,$au,$amountorg,$fee,$gatewaymodule); 
        logTransaction($GATEWAY['name'],$_POST,'Successful'); 
	
	}
	else {
		logTransaction($GATEWAY["name"], $_POST, "Unsuccessful");
	}
	}
	else {
		logTransaction($GATEWAY["name"], $_POST, "Unsuccessful");
	}
	Header('Location: ' . $CONFIG['SystemURL'] . '/viewinvoice.php?id=' . $invoiceid);
?>