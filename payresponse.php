<?php
//print_r($_REQUEST);
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

$pay_id=$_REQUEST['payment_id'];
$req=$_REQUEST['payment_request_id'];
//print_r($_REQUEST);
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payments/'.$pay_id.'/');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:YOUR_API_KEY",
                  "X-Auth-Token:YOUR_AUTH_KEY"));

$response = curl_exec($ch);
curl_close($ch); 

$json=json_decode($response,TRUE);
if($json['success']==true){
	echo "payment done";
	$status=$json['payment'];
	$up=mysql_query("UPDATE `payment` SET `status`='".$status['status']."' WHERE id='".$req."'");
}
else{
	echo "payment failed";
}