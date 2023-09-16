<?php
class SMS{
	
	private $api_user = 'alpha_otp';
	private $api_token = '1eabba3e5e5b5f982a74dd185f71c7a5';
	
	public function sendSMS($to, $msg){
		
		$params = array('app'=>'ws', 'u'=>$this->api_user, 'h'=>$this->api_token, 'op'=>'pv', 'to'=>$to, 'msg'=>$msg);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://alphasms.biz/index.php?".http_build_query($params, '', '&'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "Accept:application/json"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close ($ch);
		
		if($response){
			
			$response = json_decode($response);
			
			if($response->data[0]->status == "OK"){
				return true;
			}
		}
		
		return false;
	}
	
}