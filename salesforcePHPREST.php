<?php

	//--------READ DATA FROM JSON OR POST-------------
	//$name = $_POST["name"];
	
	$json = file_get_contents('[json file]');	
	$json = json_decode($json);
	
	$field1 = $json[0]->field1;
	$field2 = $json[0]->field2;
	
	//--------CREDENTIALS---------------------
	$authToken = "";
	$clientId = "[client_id from Salesforce]";
	$clientSecret = "[client_secret from Salesforce]";
	$username = "[username]";
	$password = "[password]";
	$securityToken = "[securityToken]";

	//--------POST TO GET ACCESS TOKEN--------
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_HTTPHEADER,array('Accept: application/json', 'Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_URL, 'https://login.salesforce.com/services/oauth2/token?grant_type=password'.
		'&client_id='.$clientId.
		'&client_secret='.$clientSecret.
		'&username='.$username.
		'&password='.$password.$securityToken);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($curl);
	curl_close($curl);
	
	$result = json_decode($result);	
	$authToken = $result->access_token;
	
	//--------GET DATA USING ACCESS TOKEN--------
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://[instance].salesforce.com/services/apexrest/objects/');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPGET, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER,array(
		'Accept: application/json',
		'Content-Type: application/json',
		'authorization: Bearer '.$authToken,
		'Content-Length: 0'
		));
	$result = curl_exec($curl);
	$getData = $result;
	curl_close($curl);
	
	//--------POST DATA USING ACCESS TOKEN--------
	$postData = array(
		'field1' => $field1,
		'field2' => $field2
	$postData = json_encode($postData);
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://[instance].salesforce.com/services/apexrest/objects/');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER,array(
		'Accept: application/json',
		'Content-Type: application/json',
		'authorization: Bearer '.$authToken
		));
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	$result = curl_exec($curl);
	curl_close($curl);
	
	//Handle results below	
?>