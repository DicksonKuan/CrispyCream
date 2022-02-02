<?php
session_start();
include("header.php"); // Include the Page Layout header
include_once("myPayPal.php"); // Include the file that contains PayPal settings
include_once("mysql_conn.php"); 

if($_POST) //Post Data received from Shopping cart page.
{
	// To Do 6 (DIY): Check to ensure each product item saved in the associative
	//                array is not out of stock
	// foreach($_SESSION["Items"] as $key=>$item) {


	// 	$qry = "SELECT * FROM Product WHERE ProductID = ?";
		
		
	// 	$stmt = $conn->prepare($qry);

	// 	$stmt->bind_param("i", $item["productId"]);
	// 	$stmt->execute();
	// 	$result=$stmt->get_result();

	// 	if($result->num_rows > 0){
	// 		while($row = $result->fetch_array()){
	// 			if ($row["Quantity"] < 20){
	// 				echo "Product $item[productId] : $item[name] is out of stock!<br />";
	// 				echo "Please return to shopping cart to amend your purchase.<br />";
	// 				include("footer.php");
	// 				exit;
	// 			}
	// 		}
	// 	}

	// 	$stmt->close();
	// 	//$conn->close();
	// }


	$qty = 0;
  //$array = json_decode(json_encode($_SESSION["Items"]), true);
    $qry = "SELECT * FROM shopcartitem WHERE ShopCartID = ?";

        $stmt = $conn->prepare($qry);

        $stmt->bind_param("i", $_SESSION["Cart"]);
        $stmt->execute();
        $result=$stmt->get_result();

        if($result->num_rows > 0){
            while($row = $result->fetch_array()){
                $qry = "SELECT Quantity FROM product WHERE ProductID = ?";
                $stmt = $conn->prepare($qry);
                $stmt->bind_param("i", $row["ProductID"]);
                if ($stmt->execute()){
                    $stmt->bind_result($qty);
                    while ($stmt->fetch()) {
                        $a = array('Quantity' => $qty);
                    }
                    $stmt->close();
                    if ($row["Quantity"] > $qty){
                        $a = $row["ProductID"];
                        $b = $row["Name"];



						echo "<link rel='stylesheet' href='css/orderConfirmed.css'";

						echo "<div class='col-sm-8'>";

						echo  "<div class='card'>";

						echo "Product $a : $b is out of stock!<br />";
                        echo "Please return to shopping cart to amend your purchase. <br />";
						echo "<a href='reviewOrder.php'>return to review order</a></p>";
						



						echo "</div>";
                        // echo $row["Quantity"];
                        include("footer.php");
                        exit;
                    }

                }
        
        }

		}
	
	// End of To Do 6
	
	$paypal_data = '';
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach($_SESSION['Items'] as $key=>$item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	
	// To Do 1A: Compute GST amount 7% for Singapore, round the figure to 2 decimal places

	//get gst amount



	

	$qry = "SELECT * from gst where EffectiveDate < curdate() order by EffectiveDate DESC limit 1;";
		
		
		$stmt = $conn->prepare($qry);

		$stmt->execute();
		$result=$stmt->get_result();

		if($result->num_rows > 0){
			while($row = $result->fetch_array()){
				// there will only be 1 row since it is limit 1
				$_SESSION["Tax"] = round($_SESSION["SubTotal"]*($row["TaxRate"]/100),2);
				
			}
		}
	
	// To Do 1B: Compute Shipping charge - S$2.00 per trip



	$radioVal = $_POST["deliveryRadio"];
	$_SESSION["radioVal"] = $_POST["deliveryRadio"];
	$_SESSION["phoneNo"] = $_POST["phoneNo"];

	//$_SESSION["shipAddress"] = $_POST["address"];
	
	
	$_SESSION["msg"] = $_POST["msg"];

	$_SESSION["email"] = $_POST["email"];
	

	$_SESSION["deliverDate"] = date("Y-m-d");
	

	$_SESSION["fname"] = $_POST["fname"];
	

	if ($radioVal == "express" ){
		//date("H") is now 24 hr HOUR
		if(date("H") < 10){
			$_SESSION['deliveryTime'] = "9am - 12noon";
			$_SESSION['deliveryDate'] = date("Y-m-d");
		}
		else if (date("H") < 13){
			$_SESSION['deliveryTime'] = "12noon - 3pm";
			$_SESSION['deliveryDate'] = date("Y-m-d");
		}
		else if (date("H") < 16){
			$_SESSION['deliveryTime'] = "3pm - 6pm";
			$_SESSION['deliveryDate'] = date("Y-m-d");
		}
		else{
			$_SESSION['deliveryTime'] = "9am - 12noon";
			$_SESSION['deliveryDate'] = date("Y-m-d", strtotime("+1 day")) ;
		}
	}
	else if ($radioVal == "normal" ){
		$_SESSION['deliveryTime'] = date("Y-m-d", strtotime("+1 day")) ;
		$_SESSION['deliveryDate'] = date("Y-m-d", strtotime("+1 day")) ;
	}


	
	
	// $radioVal = $_POST["deliveryRadio"];

	if($radioVal == 'express')
	{
		//debug_to_console("express");
		$_SESSION["ShipCharge"] = 5;
	}
	else if ($radioVal == 'normal')
	{
		if ($_SESSION["SubTotal"] < 40){
			$_SESSION["ShipCharge"] =  2;
		}
		else{
			$_SESSION["ShipCharge"] = 0;
		}
		
	}

	//$_SESSION["ShipCharge"] = 2;
	
	//Data to be sent to PayPal
	$padata = '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTACTION=Sale'.
			  '&ALLOWNOTE=1'.
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] +
				                                 $_SESSION["Tax"] + 
												 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]). 
			  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]). 
			  '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]). 	
			  '&BRANDNAME='.urlencode("Crispy Cream").
			  $paypal_data.				
			  '&RETURNURL='.urlencode($PayPalReturnURL ).
			  '&CANCELURL='.urlencode($PayPalCancelURL);	
		
	//We need to execute the "SetExpressCheckOut" method to obtain paypal token
	$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
	                                   $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
	//Respond according to message we receive from Paypal
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
		if($PayPalMode=='sandbox')
			$paypalmode = '.sandbox';
		else
			$paypalmode = '';
				
		//Redirect user to PayPal store with Token received.
		$paypalurl ='https://www'.$paypalmode. 
		            '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.
					$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}
	else {
		//Show error message
		echo "<div style='color:red'><b>SetExpressCheckOut failed : </b>".
		      urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."</div>";
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"])) 
{	
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	$paypal_data = '';
	
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach($_SESSION['Items'] as $key=>$item) 
	{
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	
	//Data to be sent to PayPal
	$padata = '&TOKEN='.urlencode($token).
			  '&PAYERID='.urlencode($playerid).
			  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
			  $paypal_data.	
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]).
              '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]).
              '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] + 
			                                     $_SESSION["Tax"] + 
								                 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	//We need to execute the "DoExpressCheckoutPayment" at this point 
	//to receive payment from user.
	$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
	                                   $PayPalApiUsername, $PayPalApiPassword, 
									   $PayPalApiSignature, $PayPalMode);
	


	// $transactionID = urlencode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
	// $nvpStr = "&TRANSACTIONID=".$transactionID;
	// $httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
	// 						$PayPalApiUsername, $PayPalApiPassword, 
	// 						$PayPalApiSignature, $PayPalMode);
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
		
		
		//$ShipCountry = "Singapore";

		if (TRUE){
			


			$transactionID = urlencode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
			$nvpStr = "&TRANSACTIONID=".$transactionID;
			$httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
								  $PayPalApiUsername, $PayPalApiPassword, 
								  $PayPalApiSignature, $PayPalMode);

					

			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
			"SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
			{
				//gennerate order entry and feed back orderID information
				//You may have more information for the generated order entry 
				//if you set those information in the PayPal test accounts.
				
				$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));
				
				$ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
				if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
					$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
				if (isset($httpParsedResponseAr["SHIPTOCITY"]))
					$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCITY"]);
				if (isset($httpParsedResponseAr["SHIPTOSTATE"]))
					$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
				$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]). 
								' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]);
					
				$ShipCountry = urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
				
				$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);			

				$_SESSION["Address"] = $ShipAddress;
				$_SESSION['Address'] = substr($_SESSION['Address'], 0, strpos($_SESSION['Address'], "SG_zip"));

				if ($ShipCountry == "Singapore"){

					$qry = "SELECT * FROM shopcartitem WHERE ShopCartID = ?";

					$stmt = $conn->prepare($qry);

					$stmt->bind_param("i", $_SESSION["Cart"]);
					$stmt->execute();
					$result=$stmt->get_result();

					if($result->num_rows > 0){
						while($row = $result->fetch_array()){
							$qry2 = 'UPDATE Product Set Quantity = Quantity-? WHERE ProductID = ?';
							$stmt2 = $conn->prepare($qry2);
							$stmt2->bind_param("dd", $row["Quantity"], $row["ProductID"]);
							$stmt2->execute();
							$stmt2->close();
						}
					}

					$stmt->close();


					
					//$conn->close();


					// End of To Do 5
				
					// To Do 2: Update shopcart table, close the shopping cart (OrderPlaced=1)
					$total = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
					$qry = 'UPDATE Shopcart SET OrderPlaced=1, Quantity=?, SubTotal=?, ShipCharge=?, Tax=?, Total=?
							WHERE ShopCartID=?';
					
					$stmt = $conn->prepare($qry);

					$stmt->bind_param("iddddi", $_SESSION["NumCartItem"], 
									$_SESSION["SubTotal"], $_SESSION["ShipCharge"],
									$_SESSION["Tax"], $total,
									$_SESSION["Cart"]);

					$stmt->execute();
					$stmt->close();


					$qry = "INSERT INTO orderdata (ShopCartID, ShipName, ShipAddress, ShipCountry,ShipPhone,
												 ShipEmail, BillName, BillAddress, BillCountry, BillPhone, BillEmail,
												  DeliveryDate, DeliveryTime, DeliveryMode, Message, DateOrdered) VALUE(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

					$stmt = $conn->prepare($qry);

					
					$stmt-> bind_param("isssssssssssssss", $_SESSION["Cart"], $_SESSION["fname"], $ShipAddress, $ShipCountry, $_SESSION["phoneNo"], $_SESSION["email"],$ShipName,$ShipAddress, $ShipCountry, $_SESSION["phoneNo"], $ShipEmail, $_SESSION['deliverDate'], $_SESSION['deliveryTime'], $_SESSION['radioVal'], $_SESSION['msg'], $_SESSION['deliverDate']);
						
					$stmt->execute();
					$stmt->close();
					$qry = "SELECT LAST_INSERT_ID() AS OrderID";
					$result = $conn->query($qry);
					$row = $result->fetch_array();
					$_SESSION["OrderID"] = $row["OrderID"];


					// End of To Do 3
						
					//$conn->close();
						
					
					$_SESSION["NumCartItem"] = 0;
					
					
					unset($_SESSION["Cart"]);
					
					
					header("Location: orderConfirmed.php");
					exit;
				}
				
				// To Do 3: Insert an Order record with shipping information
				//          Get the Order ID and save it in session variable.

				else{
					echo "<div style='color:red'><b>Im sorry we currently do not send to $ShipCountry!</b></div>";
					echo "<div style='color:red'><b>The order process has been stopped. You can return to the review order page to make amends </b></div>";
					echo "<a href='reviewOrder.php'>return to review order</a></p>";
					
				//echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
				//$conn->close();
				}

		

				
			} 
			
			else 
			{
				echo "<div style='color:red'><b>GetTransactionDetails failed:</b>".
			                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
			}
		}

		

		else 
		{
			echo "<div style='color:red'><b>Im sorry we currently do not send to $ShipCountry!</b></div>";
			echo "<div style='color:red'><b>The order process has been stopped. You can return to the review order page to make amends </b></div>";
			echo "<a href='reviewOrder.php'>return to review order</a></p>";
		}
	}
	else {
		echo "<div style='color:red'><b>DoExpressCheckoutPayment failed : </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

include("footer.php"); // Include the Page Layout footer
?>



