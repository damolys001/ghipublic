<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

  // Replace contact@example.com with your real receiving email address
  $receiving_email_address = 'info@ghiassets.com';

  $name ="";
  $email ="";
  $phone ="";
  $message="";
  $subject="GHI Assets: Customer is requesting for a call";
  $RequestType="";
  
  if (isset($_REQUEST["name"]))$name = $_REQUEST["name"];
  if (isset($_REQUEST["email"]))$email = $_REQUEST["email"];
  if (isset($_REQUEST["phone"]))$phone = $_REQUEST["phone"];
  if (isset($_REQUEST["message"]))$message = $_REQUEST["message"];
  if (isset($_REQUEST["subject"]))$subject = $_REQUEST["subject"];
  if (isset($_REQUEST["RequestType"]))$RequestType = $_REQUEST["RequestType"];

   
  $messageEmail = "
    <html>
    <head>
    <title>GHI Assets: Customer is requesting for a call</title>
    </head>
    <body>
    <p>Please find the detail of customer requesting for a call below </p>
    <table>
    <tr>
    <td><b>Name:</b></td>
    <td>$name</td>
    </tr>
    <tr>
    <td><b>Email:</b></td>
    <td>$email</td>
    </tr>
    <tr>
    <td><b>Phone:</b></td>
    <td>$phone</td>
    </tr>
    <tr>
    <td><b>Message:</b></td>
    <td>$message</td>
    </tr>
    </table>
    </body>
    </html>
"; 


  // Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From:Website<no-reply@ghiassets.com>' . "\r\n";




$recaptcha = $_POST['g-recaptcha-response'];
$res = reCaptcha($recaptcha);
$msg ="";
if($res['success'])
{
    $result = mail($receiving_email_address,$subject,$messageEmail,$headers);
    if($result == true)
    {
      $msg = "Your message was sent successfuly";
    }
    else
    {
      $msg = "There was error sending your message, please try again latter";
    }
}else{
  $msg = "Please validate you are not a robot";
}


function reCaptcha($recaptcha){
  $secret = "6Ldw-KolAAAAALNtvY8k3K4H9S7JM_8n6iB9-pj9";
  $ip = $_SERVER['REMOTE_ADDR'];

  $postvars = array("secret"=>$secret, "response"=>$recaptcha, "remoteip"=>$ip);
  $url = "https://www.google.com/recaptcha/api/siteverify";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
  $data = curl_exec($ch);
  curl_close($ch);

  return json_decode($data, true);
}


$data = array("status" =>$res['success'], "msg" => $msg );

header("Content-Type: application/json");
echo json_encode($data);

exit();



?>
