<?php
//database connection
$con=mysql_connect("localhost","root","");
if($con){
    echo "Database Host Connected<br>";
}
else{
    echo mysql_error();
    die();
}
$ch2=mysql_select_db("payment",$con);
if($ch2){
    echo "Database Connected<br>";
}
else{
    echo mysql_error();
    die();
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:YOUR_API_KEY",
                  "X-Auth-Token:YOUR_AUTH_KEY"));
$payload = Array(
    'purpose' => $_REQUEST['purpose'],
    'amount' => $_REQUEST['amount'],
    'phone' => $_REQUEST['phone'],
    'buyer_name' => $_REQUEST['buyer_name'],
    'redirect_url' => 'https://postlocaldata.000webhostapp.com/post.php',
    'send_email' => true,
    'webhook' => 'https://postlocaldata.000webhostapp.com/post.php',
    'send_sms' => true,
    'email' => $_REQUEST['email'],
    'allow_repeated_payments' => false
);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
$response = curl_exec($ch);
curl_close($ch); 

//echo $response;
$payment=json_decode($response,true);
if($payment['success']==true){
    echo "success";
    //Payment Data
    $successdata=$payment['payment_request'];

    $qr=mysql_query("INSERT INTO `payment`(`id`, `phone`, `email`, `buyer_name`, `amount`, `purpose`, `status`) VALUES ('".$successdata['id']."','".$successdata['phone']."','".$successdata['email']."','".$successdata['buyer_name']."','".$successdata['amount']."','".$successdata['purpose']."','".$successdata['status']."')");
    if($qr){
        echo "Inserted<br>";
        echo "<script>location='".$successdata['longurl']."'</script>";
    }
    else{
      echo mysql_error();
      die();
    }
}
else{
    echo "failed to create order";
}
//===================test card=======================
//=======no. 4242 4242 4242 4242========================
//========cvv 111 ======================
// ============ date = any future dat =========================
//===========code 1221 =========================

?>