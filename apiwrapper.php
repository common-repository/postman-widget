<?php
/*
	This file is part of Postman Widget.

    Postman Widget is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Postman Widget is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Postman Widget.  If not, see <http://www.gnu.org/licenses/>.
	*/
	
function storeAddress(){
	
	// Validation
	if(!$_POST['email']){ return "No email address provided"; } 

	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_POST['email'])) {
		return "Email address is invalid"; 
	}

	require_once('lib/nusoap.php');
	if($_POST['api_key'] == '')
	{
		return 'No API key found, please set API from Widget section';
	}
	
	$paloma_customer_hash = $_POST['api_key'];		

	if($_POST['customer'] == '')
	{
		return 'No Customer ID found, please set from Widget section';
	}
	
	$paloma_customer_id = $_POST['customer'];		
	
	if($_POST['list_id'] == '')
	{
		return 'No List ID found, please set List ID from Widget section';
	}
	
	$list_id = $_POST['list_id'];
	
	try
	{
		$client = new SoapClient("https://api.paloma.se/PalomaWebService.asmx?WSDL");
		$results = $client->InsertSubscribers(
												array(
														'customerID' => $paloma_customer_id,
														'customerHash' => $paloma_customer_hash, 
														'addressListID' => $list_id, 
														'subscribers' => array(
																			'Email' => $_POST['email'],
																			'TimeStamp' => new DateTime,
																			'Registered' => true
																		)
													)
								);
		// Todo: Check that the code after this return works, and enable it then
		return $results->InsertSubscribersResponse;
		// Check for a fault
		if ($client->fault) {
			echo '<h2>Fault</h2><pre>';
			print_r($result);
			echo '</pre>';
		} else {
			// Check for errors
			$err = $client->getError();
			if ($err) {
				// Display the error
				echo '<h2>Error</h2><pre>' . $err . '</pre>';
			} else {
				// Display the result
				echo '<h2>Result</h2><pre>';
				print_r($result);
				echo '</pre>';
			}
		}
		return $results["InsertSubscribersResponse"];
	}
	catch(Exception $e)
	{
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}

echo storeAddress();
?>
