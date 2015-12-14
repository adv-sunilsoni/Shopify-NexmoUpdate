<?php


 class Shopify
 {
	
	public function __construct(){
        
		
        }
	
	public function handler($event,$key,$secret,$fromuser,$allData,$threshold,$storename,$ownerno)
	{
		
		
			$amount=$allData["total_price"];
			$url="";
			
		if($amount>=$threshold)
		{		
			if($event == "order_creation"){
				
				//http://52.34.200.198/sunil_home/index.php?framework=shopify&event=order_creation&key=5b2a23d7&secret=59d9fa03&from=919222010111&threshold=100&storename&ownerno=919782177245	
				$onumber=$allData["order_number"];
				
				$currency=$allData["currency"];
			
				$customerInfo=$allData["customer"];
				$cname=$customerInfo["first_name"]."+".$customerInfo["last_name"];
			
				//a new order of amount 00 is created;
				//
				$message="The+order+".$onumber."+is+created+of+amount+".$currency."+".$amount."+by+".$cname;
				$url="https://rest.nexmo.com/sms/json?api_key=".$key."&api_secret=".$secret."&from=".$fromuser."&to=".$ownerno."&text=".$message;
				$data = file_get_contents($url);
				return $url;
				
			}
			
			if($event == "order_fulfillment"){
				
				//http://52.34.200.198/sunil_home/index.php?framework=shopify&event=order_fulfillment&key=5b2a23d7&secret=59d9fa03&from=919222010111&threshold=100&storename&ownerno=919460264151	
				$onumber=$allData["order_number"];
				
				$customerInfo=$allData["billing_address"];
				
				//assuming the vendor is shipper who ships and deliver the product
			
				$cname=$customerInfo["first_name"]."+".$customerInfo["last_name"];
				
				//a new order of amount 00 is created;
				//
				//MessageforStoreOwner
				$storeownermessage="The+order+".$onumber."+is+fulfilled+for+".$cname;
				$url="https://rest.nexmo.com/sms/json?api_key=".$key."&api_secret=".$secret."&from=".$fromuser."&to=".$ownerno."&text=".$storeownermessage;
				$data = file_get_contents($url);
				
				//Message for Customer
				$customerphone=$customerInfo["phone"];
				$customermessage="The+order+".$onumber."+is+fulfilled+by+".$storename;
				$url="https://rest.nexmo.com/sms/json?api_key=".$key."&api_secret=".$secret."&from=".$fromuser."&to=".$customerphone."&text=".$customermessage;
				$data = file_get_contents($url);
				return $url;
				
			}
			if($event == "order_cancelled"){
				
				////http://52.34.200.198/sunil_home/index.php?framework=shopify&event=order_cancelled&key=5b2a23d7&secret=59d9fa03&from=919222010111%threshold=100&storename&ownerno=919460264151	
				$onumber=$allData["order_number"];
				$cancelReason=$allData["cancel_reason"];
					$toOwnerMessage="";
					$toCustomerMessage="";
				if($cancelReason=="inventory"){
						$toOwnerMessage="The+order+".$onumber."+has+been+cancelled because+of+inventory.";
					$toCustomerMessage="The+order+".$onumber."+has+been+cancelled+because+we+did+not+have+enough+stock+to+fulfill+your+order+at+".$storename;
				}
				else if($cancelReason=="fraud"){
					$toOwnerMessage="The+order+".$onumber."+has+been+cancelled.";
					$toCustomerMessage="The+order+".$onumber."+has+been+cancelled+at+".$storename;
				}
				else{
					$toOwnerMessage="The+order+".$onumber."+has+been+cancelled.";
					$toCustomerMessage="The+order+".$onumber."+has+been+cancelled+at+".$storename;
				}
				
				//because we did not have enough stock to fulfill your order
				//to store owner
				
				$url="https://rest.nexmo.com/sms/json?api_key=".$key."&api_secret=".$secret."&from=".$fromuser."&to=".$ownerno."&text=".$toOwnerMessage;
				$data = file_get_contents($url);
				
				
				//to customer 
			
				$customerInfo=$allData["billing_address"];
				$customerphone=$customerInfo["phone"];
				$url1="https://rest.nexmo.com/sms/json?api_key=".$key."&api_secret=".$secret."&from=".$fromuser."&to=".$customerphone."&text=".$toCustomerMessage;
				$data = file_get_contents($url1);
				
			
				return $url." and ".$url1;
				
			}
			if($event == "order_deleted"){
				
			//http://52.34.200.198/sunil_home/index.php?framework=shopify&event=order_deleted&key=5b2a23d7&secret=59d9fa03&from=919222010111&threshold=100&storename&ownerno=919460264151	
				$onumber=$allData["order_number"];
				
				$customerInfo=$allData["customer"];
				$cname=$customerInfo["first_name"]."+".$customerInfo["last_name"];
			
				//a new order of amount 00 is created;
				//
				$message="The+order+".$onumber."+has+been+deleted+";
				$url="https://rest.nexmo.com/sms/json?api_key=".$key."&api_secret=".$secret."&from=".$fromuser."&to=".$ownerno."&text=".$message;
				$data = file_get_contents($url);
				return $url;
				
			}
			if($event == "order_paid"){
				
			    //http://52.34.200.198/sunil_home/index.php?framework=shopify&event=order_paid&key=5b2a23d7&secret=59d9fa03&from=919222010111&threshold=100&storename&ownerno=919460264151	
				$isPaid=$allData["financial_status"];
				
				if($isPaid=="paid"){
				$onumber=$allData["order_number"];
				$currency=$allData["currency"];
				$message="The+payment+of+order+".$onumber."+of+amount+".$currency."+".$amount."+has+been+received";
				$url="https://rest.nexmo.com/sms/json?api_key=".$key."&api_secret=".$secret."&from=".$fromuser."&to=".$ownerno."&text=".$message;
				$data = file_get_contents($url);
				}
				else{
					echo "financial status is not paid";
				}
				return $url;
				
			}
		
		}
		else{
			echo "Threshold value is not reached";
		}
	}

	
 }


?>