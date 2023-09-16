<?php

require_once("PaymentProcessor.php");

class PayPalPay extends PaymentProcessor{
	
	private $paypal_user = 'payments_api1.sineris.com';
	
	private $paypal_pwd = '5CRG9DE4V32KPALV';
	
	private $paypal_signature = 'A-q8H1b-tRdALm6DU0SV8Y6DtxzgAtCZYVokn0qxr0.unCI9-xQ8VcCU';
	
	private $ipx_account_id = 7;
	
	
	public function isValid($methodinfo){
		
		if(isset($_POST['paypal_txid']) && !empty($_POST['paypal_txid'])){
			
			//Search Transaction in Database
			$searchTx = $this->db->Query("SELECT * FROM paypal_transaction WHERE txid=? AND txid NOT IN (SELECT gateway_txid FROM transaction WHERE gateway_txid=? AND method_id='1')", $_POST['paypal_txid'], $_POST['paypal_txid']);
			
			if($searchTx->numRows() > 0){
				
				$paypalTx = $searchTx->fetchArray();
				switch ($paypalTx['status']){
					case 'Completed':
						$status ='Complete';
						break;
						  
					case 'Cancelled':
					case 'Canceled':
						$status ='Canceled';
						break;
							
					default:
						$status = 'Pending';
				}
				
				$receiver = $this->db->Query("SELECT a.id FROM account AS a JOIN paypal_transaction AS p ON p.receiver=a.account WHERE a.account=?", $paypalTx['receiver'])->fetchArray();
				
				$data['gateway_txid'] = $paypalTx['txid'];
				$data['amount'] = $paypalTx['amount'];
				$data['status'] = $status;
				$data['ipx_account'] = $receiver['id'];
				$data['fee'] = $paypalTx['fee'];
				$data['result'] = 'OK';
				return $data;
				
			}
			
			
			$data['result'] = 'FORM_ERROR';
			
			$txExists = $this->db->Query("SELECT status FROM transaction WHERE gateway_txid=? AND method_id='1'", $_POST['paypal_txid']);
			if($txExists->numRows() > 0){
				
				$tx = $txExists->fetchArray();
				$data['alert'][] = array('type'=>'error', "msg"=>"Transaction already ".$tx['status']);
				
			}else{
				
				$data['alert'][] = array('type'=>'error', "msg"=>"Invalid Transaction ID.");
			}
			
			return $data;
			
			
			
			
			
		}elseif(isset($_POST['paypal_amount'])){
			
			$amount = floatval($_POST['paypal_amount']);
			
			if($amount > $methodinfo['buying_max'] || $amount < $methodinfo['buying_min']){
				
				$data['error']['active_tab'] = 'checkout';
				$data['result'] = 'FORM_ERROR';
				$data['error']['paypal_amount'] = 'Please enter amount between '.$methodinfo['buying_min'].' and '.$methodinfo['buying_max'];
				return $data;
			}
			
			
			$data['user'] = $this->paypal_user;
			$data['pwd'] = $this->paypal_pwd;
			$data['signature'] = $this->paypal_signature;
			$data['version'] = 113;
			$data['method'] = 'SetExpressCheckout';
			$data['paymentrequest_0_paymentaction'] = 'sale';
			//$data['paymentrequest_0_amt'] = 0.01;
			$data['paymentrequest_0_amt'] = $amount;
			$data['paymentrequest_0_currencycode'] = 'USD';
			$data['returnurl'] = 'https://ipxwallet.com/paypal/success/';
			$data['cancelurl'] = 'https://ipxwallet.com/paypal/cancel/';
			
			$curl = curl_init("https://api-3t.paypal.com/nvp");

			$options = array(
				CURLOPT_VERBOSE  => true,
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_POSTFIELDS  => http_build_query($data),
				CURLOPT_CUSTOMREQUEST  => "POST",
				CURLOPT_TIMEOUT  => 10
			);

			curl_setopt_array($curl, $options);
			$rep = curl_exec($curl);
			parse_str($rep, $response);
			curl_close($curl);
			
			if($response['ACK'] == 'Success'){
				
				$request = $this->db->Query("INSERT INTO paypal_checkout (token, user_id, amount, created) VALUES (?, ?, ?, ?)", $response['TOKEN'], $_SESSION['userid'], $amount, TIMESTAMP);
				
				if($request){
					
					Util::redirect('https://www.paypal.com/webscr?cmd=_express-checkout&token='.$response['TOKEN']);
				}
			}
				
				
			$data['error']['active_tab'] = 'checkout';
			$data['result'] = 'SYSTEM_ERROR';
			$data['alert'][] = array('type'=>'error', "msg"=>"System error! Please try again later.");
			return $data;
			
				
		}elseif(isset($_POST['token']) && isset($_POST['PayerID'])){
			
			$request = $this->db->Query("SELECT * FROM paypal_checkout WHERE token=?", $_POST['token']);
			
			if($request->numRows() > 0){
			
				$request =  $request->fetchArray();
				
				$data['user'] = $this->paypal_user;
				$data['pwd'] = $this->paypal_pwd;
				$data['signature'] = $this->paypal_signature;
				$data['version'] = 113;
				$data['method'] = 'DoExpressCheckoutPayment';
				$data['paymentrequest_0_paymentaction'] = 'sale';
				$data['paymentrequest_0_amt'] = $request['amount'];
				//$data['paymentrequest_0_amt'] = 0.01;
				$data['paymentrequest_0_currencycode'] = 'USD';
				$data['token'] = $_POST['token'];
				$data['payerid'] = $_POST['PayerID'];
				
				$curl = curl_init("https://api-3t.paypal.com/nvp");

				$options = array(
					CURLOPT_VERBOSE  => true,
					CURLOPT_RETURNTRANSFER  => true,
					CURLOPT_POSTFIELDS  => http_build_query($data),
					CURLOPT_CUSTOMREQUEST  => "POST",
					CURLOPT_TIMEOUT  => 10
				);
				
				
				curl_setopt_array($curl, $options);
				$rep = curl_exec($curl);
				parse_str($rep, $response);
				curl_close($curl);
				
				if(isset($response['PAYMENTINFO_0_PAYMENTSTATUS'])){
					
					$data['gateway_txid'] = $response['PAYMENTINFO_0_TRANSACTIONID'];
					$data['amount'] = $response['PAYMENTINFO_0_AMT'];
					$data['status'] = ($response['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed' ? 'Complete' : 'Pending');
					$data['ipx_account'] = $this->ipx_account_id;
					$data['fee'] = $response['PAYMENTINFO_0_FEEAMT'];
					$data['user_id'] = $request['user_id'];
					$data['result'] = 'OK';
					return $data;
					
					
				}else{
					
					$data['alert'][] = array("type"=>"error", "msg"=>"Payment could not be verified.");
					$data['result'] = 'PAYMENT_ERROR';
					return $data;
				
				}
				
				
			}else{
				
				$data['alert'][] = array("type"=>"error", "msg"=>"Payment not identified");
				$data['result'] = 'PAYMENT_ERROR';
				return $data;
			}
			
			
			
		}else{
			
			$data['alert'][] = array("type"=>"error", "msg"=>"Invalid Request.");
			$data['result'] = 'FORM_ERROR';
			return $data;
		}
		
		
	}
	
	
}