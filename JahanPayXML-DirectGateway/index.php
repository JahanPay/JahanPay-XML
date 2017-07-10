<?php
error_reporting(E_ERROR);
@session_start();

$unique_id = uniqid();

$callback = 'http://localhost/a_test/jahanpayxml/callback.php?uid='.$unique_id;

$api ='gtd24587u6';
$price = '100';
$order_id = 1;


//make xml

$data = "<?xml version='1.0'?> 
<document>
	<api>{$api}</api>
	<price>{$price}</price>
	<callback>{$callback}</callback>
	<order_id>{$order_id}</order_id>
</document>";

$url = 'http://www.jpws.me/directservicexml/requestpayment';

$postdata = http_build_query(array('data' => $data));
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);
$context  = stream_context_create($opts);
$result = (string) file_get_contents($url, false, $context);

$return = simplexml_load_string($result);

if($return->result==1)
{
	//save
	$_SESSION[$unique_id] = array(
		'price'=>$price ,
		'au'=>(string) $return->au //کد پيگيري جهان پي
	);
	
	echo htmlspecialchars_decode($return->form);
}
else
{
	echo "error code ".$return->result;
	echo "<br>";
	echo $return->msg;
}