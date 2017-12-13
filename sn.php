<?php
session_start();
	$MerchantID = $_POST['merchantID'];
	$WebService = $_POST['WebService'];
	$amount = strtok($_POST['amount'], '.');
	$amountorg = strtok($_POST['amount'], '.');
	$Description = $_POST['description'];
	$Paymenter = urlencode($_POST['Paymenter']);
	$Email = $_POST['Email'];
	$Mobile = $_POST['Mobile'];
	$ResNumber = $_POST['invoiceid'];
	if ($_POST['currencies'] == 'Rial'){
		$amount = $amount / 10;
	$amount = ceil($amount);}
					// Security
					$sec = uniqid();
					$md = md5($sec.'vm');
					// Security
	$callBackUrl = $_POST['systemurl'] . '/modules/gateways/callback/sn.php?invoiceid=' . $ResNumber . '&amount=' . $amount. '&sec=' . $sec . '&md=' . $md;

						if ($WebService == 'on'){
						    
				    if($Email==''){$Email='0'; }
				     if($Paymenter==''){$Paymenter='0';}
				      if($Mobile==''){$Mobile='0';}
				       if($Description==''){$Description='0';}
				       
					   	$data_string = json_encode(array(
					'pin'=> $MerchantID,
					'price'=> $amount,
					'callback'=>$callBackUrl ,
					'order_id'=> $ResNumber,
					'email'=> $Email,
					'description'=> $Description,
					'name'=> $Paymenter,
					'mobile'=> $Mobile,
					'ip'=> $_SERVER['REMOTE_ADDR'],
					'callback_type'=>2
					));
				    
			        }
					else
					{
					   	$data_string = json_encode(array(
					'pin'=> $MerchantID,
					'price'=> $amount,
					'callback'=>$callBackUrl ,
					'order_id'=> $ResNumber,
					'email'=> '0',
					'description'=> $Description,
					'name'=> '0',
					'mobile'=> '0',
					'ip'=> $_SERVER['REMOTE_ADDR'],
					'callback_type'=>2
					));
					    
					}
				

					$ch = curl_init('https://developerapi.net/api/v1/request');
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

		 $res=$json['result'];
                 
	                 switch ($res) {
						    case -1:
						    $msg = "پارامترهای ارسالی برای متد مورد نظر ناقص یا خالی هستند . پارمترهای اجباری باید ارسال گردد";
						    break;
						     case -2:
						    $msg = "دسترسی api برای شما مسدود است";
						    break;
						     case -6:
						    $msg = "عدم توانایی اتصال به گیت وی بانک از سمت وبسرویس";
						    break;
						     case -9:
						    $msg = "خطای ناشناخته";
						    break;
						     case -20:
						    $msg = "پین نامعتبر";
						    break;
						     case -21:
						    $msg = "ip نامعتبر";
						    break;
						     case -22:
						    $msg = "مبلغ وارد شده کمتر از حداقل مجاز میباشد";
						    break;
						    case -23:
						    $msg = "مبلغ وارد شده بیشتر از حداکثر مبلغ مجاز هست";
						    break;
						      case -24:
						    $msg = "مبلغ وارد شده نامعتبر";
						    break;
						      case -26:
						    $msg = "درگاه غیرفعال است";
						    break;
						      case -27:
						    $msg = "آی پی مسدود شده است";
						    break;
						      case -28:
						    $msg = "آدرس کال بک نامعتبر است ، احتمال مغایرت با آدرس ثبت شده";
						    break;
						      case -29:
						    $msg = "آدرس کال بک خالی یا نامعتبر است";
						    break;
						      case -30:
						    $msg = "چنین تراکنشی یافت نشد";
						    break;
						      case -31:
						    $msg = "تراکنش ناموفق است";
						    break;
						      case -32:
						    $msg = "مغایرت مبالغ اعلام شده با مبلغ تراکنش";
						    break;
						      case -35:
						    $msg = "شناسه فاکتور اعلامی order_id نامعتبر است";
						    break;
						      case -36:
						    $msg = "پارامترهای برگشتی بانک bank_return نامعتبر است";
						    break;
						        case -38:
						    $msg = "تراکنش برای چندمین بار وریفای شده است";
						    break;
						      case -39:
						    $msg = "تراکنش در حال انجام است";
						    break;
                            case 1:
						    $msg = "پرداخت با موفقیت انجام گردید.";
						    break;
						    default:
						       $msg = $josn['result'];
						}
			if(!empty($json['result']) AND $json['result'] == 1)
			{ 
			// Set Session
			$_SESSION[$sec] = [
				'price'=>$amount ,
				'order_id'=>$ResNumber ,
				'au'=>$json['au'] ,
				'amount'=>$amountorg ,
			];

									echo "<div style='display:none'>{$json['form']}</div>Please wait ... <script language='javascript'>document.payment.submit(); </script>";
	}
	else {
		$mess = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>خطا</title>
        <link href="templates/default/css/bootstrap.css" rel="stylesheet">
        <link href="templates/default/css/whmcs.css" rel="stylesheet">
  </head>
  <body>
    <div class="whmcscontainer">
        <div class="contentpadded">
        <div class="alert alert-error" dir="rtl" style="font-family:tahoma; font-weight:bold">
                خطا در اتصال به وب سرويس ! وضعيت خطا : ' . $msg  . '
        </div>
        </div>
    </div>
</body>
</html>';
		echo $mess;
	}
?>