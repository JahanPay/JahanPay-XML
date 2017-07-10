<?php
error_reporting(E_ERROR);
session_start();

$save = $_SESSION[$_GET['uid']];

$api ='gtd24586';
$price = $save['price'];
$au = $save['au'];
$order_id = 1;


//bank return

$_d = $_POST + $_GET;
$str = '';

foreach($_d as $key=>$val)
	$str .= "<{$key}>{$val}</{$key}>";

$data = "<?xml version='1.0'?> 
<document>
	<api>{$api}</api>
	<price>{$price}</price>
	<au>{$au}</au>
	<order_id>{$order_id}</order_id>
	<bank_return>
		{$str}
	</bank_return>
</document>";

$url = 'http://www.jpws.me/directservicexml/verification';

$postdata = http_build_query(array('data' => $data));
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);
$context  = stream_context_create($opts);
$result = file_get_contents($url, false, $context);

$return = simplexml_load_string($result);
if($return->result==1)
{
	//save
	echo "pay ok .<br>";
	echo "bank au : ".$return->bank_au;
	echo "<br>jahanpay au : ".$au;
}
else
{
	echo "error code ".$return->result;
	echo "<br>";
	echo $return->msg;
}