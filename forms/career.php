<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

  // Replace contact@example.com with your real receiving email address
  $receiving_email_address = 'hr@ghiassets.com';


  $fname = "";
  $lname = "";
  $phone = "";
  $email = "";
  $address = "";
  $qualification = "";
  $relocate = "";
  $skill = "";
  $salary = "";

  $applicationName = "";
  
  if (isset($_REQUEST["fname"])) $fname = $_REQUEST["fname"];
  if (isset($_REQUEST["lname"])) $lname = $_REQUEST["lname"];
  if (isset($_REQUEST["phone"])) $phone = $_REQUEST["phone"];
  if (isset($_REQUEST["email"])) $email = $_REQUEST["email"];
  if (isset($_REQUEST["address"])) $address = $_REQUEST["address"];
  if (isset($_REQUEST["qualification"])) $qualification = $_REQUEST["qualification"];
  if (isset($_REQUEST["relocate"])) $relocate = $_REQUEST["relocate"];
  if (isset($_REQUEST["skills"])) $skill = $_REQUEST["skills"];
  if (isset($_REQUEST["salary"])) $salary = $_REQUEST["salary"];
  if (isset($_REQUEST["applicationName"])) $applicationName = $_REQUEST["applicationName"];
  if (isset($_FILES["cvFile"])) {
      $attachment = $_FILES["cvFile"];
  }

  $subject = "GHI Assets: Application  For " .$applicationName. " Role";
  if($applicationName=="")
  {
    "GHI Assets: Generic Job Application";
  }
  
  $messageEmail = "
      <html>
      <head>
      <title>GHI Assets: Customer is requesting for a call</title>
      </head>
      <body>
      <p>Please find the details of the customer requesting a call below:</p>
      <table>
      <tr>
      <td><b>First Name:</b></td>
      <td>$fname</td>
      </tr>
      <tr>
      <td><b>Last Name:</b></td>
      <td>$lname</td>
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
      <td><b>Address:</b></td>
      <td>$address</td>
      </tr>
      <tr>
      <td><b>Qualification:</b></td>
      <td>$qualification</td>
      </tr>
      <tr>
      <td><b>Skill:</b></td>
      <td>$skill</td>
      </tr>
      <tr>
      <td><b>Salary Expection:</b></td>
      <td>$salary</td>
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
  $msg = "";
  
  if ($res['success']) {
      $result = mailWithAttachment($receiving_email_address, $subject, $messageEmail, $headers, $attachment);
      if ($result == true) {
          $msg = "Your Job Application Was Successfully";
      } else {
          $msg = "There was an error, please try again later";
      }
  } else {
      $msg = "Please validate that you are not a robot";
  }
  
  function mailWithAttachment($to, $subject, $message, $headers, $attachment) {
      $boundary = md5(time());
  
      $headers .= "\r\nMIME-Version: 1.0\r\n";
      $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
      
      $message = "--$boundary\r\n" . "Content-Type: text/html; charset=\"UTF-8\"\r\n" .
          "Content-Transfer-Encoding: 7bit\r\n\r\n" .
          $message . "\r\n\r\n";
  
      if (!empty($attachment)) {
          $filename = $attachment["name"];
          $file_content = file_get_contents($attachment["tmp_name"]);
          $file_content = chunk_split(base64_encode($file_content));
  
          $message .= "--$boundary\r\n";
          $message .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n" . 
              "Content-Disposition: attachment; filename=\"$filename\"\r\n" . 
              "Content-Transfer-Encoding: base64\r\n\r\n" .
              $file_content . "\r\n\r\n";
      }
  
      $message .= "--$boundary--";
  
      return mail($to, $subject, $message, $headers);
  }
  
  // Rest of the code remains the same
  

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
