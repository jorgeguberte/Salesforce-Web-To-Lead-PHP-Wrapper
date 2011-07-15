<?php
/**
 * Classe que insere um novo 'lead' no Salesforce via WebToLead. 
 * 
 * @author Jorge Guberte <jorge.guberte@egypteam.com>
 * @version 1.0
 */

class SFWebtoLead{
	/**
	 * 
	 * Classe única.
	 * @var string $_oid
	 * @var string
	 * 
	 */
	private $_oid;
	private $_SFServletURL;

	
	function SFWebtoLead(){		
		//Configuração
		$this->_oid = ''; // OID do Salesforce
		$this->_SFServletURL = "http://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8";
		
	}

	function Send(array $args){
		$outboundArgs = array('oid'=>$this->_oid);
		
		foreach($args as $key=>$value){
			if($key !== "submit")
				$outboundArgs[stripslashes($key)] = stripslashes($value);
		}
		
		if(!$this->_curlSend($outboundArgs)){
			return False;
		}else{
			return True;
		}
	}
	
	
	private function _curlSend($outboundArgs){
		if(!function_exists('curl_init')){
			return false;
		}
		$ch = curl_init();
		if(curl_error($ch) != ""){
			return false;
		}
		
		try{
			curl_setopt($ch, CURLOPT_URL, $this->_SFServletURL);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($outboundArgs));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		}catch(Exception $e){
			return false;
		}
		
		try{
			$res = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if($httpCode == '200'){
				return True;
			}else{
				return False;
			}
		}catch(Exception $e){
			return false;
		}
		return true;
		
	}
	
	
	
}
?>