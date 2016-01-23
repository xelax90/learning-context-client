<?php

/*
 * Copyright (C) 2016 schurix
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace LearningContextClient;

/**
 * Description of ContextData
 *
 * @author schurix
 */
class ContextData {
	/**
	 * @var Config
	 */
	protected $config;
	
	public function __construct(Config $config) {
		$this->config = $config;
	}

	/**
	 * @return Config
	 */
	public function getConfig(){
		return $this->config;
	}
	
	/**
	 * Generating a random string which will be used as nonce
	 * @return string
	 */
	protected function genNonce() {
		$chars = '1234567890abcdefghijklmnopqrstuvwxyz';
		$rnd_string = '';
		$num_chars = strlen($chars);
		$size = mt_rand(41, 59);
		for($i=0; $i<$size; $i++) {
			$rnd_string .= $chars[mt_rand(0, $num_chars - 1)];
		}
		return $rnd_string;
	}
	
	/**
	 * Generating the required hash value from the data, nonce and the other information that is nearly static
	 * @param string $data
	 * @param string $nonce
	 * @return string
	 */
	protected function genHash($data, $nonce) {
		$token = $this->getConfig()->getStorage()->getAccessToken()->getAccessToken();
		return sha1(urlencode($data) . $this->getConfig()->getAppId() . urlencode(sha1($token)) . urlencode($nonce) . $this->getConfig()->getAppSecret() . $token);
	}
	
	/**
	 * Normalizing the name of the interface, in case there is a / at the start
	 * @param string $interface
	 * @return string
	 */
	protected function normalizeInterface($interface) {
		return '/'.trim($interface, '/');
	}
	
	protected function genInterfaceUrl($interface){
		return $this->getConfig()->getApiUrl().'/'.$this->getConfig()->getApiVersion().$this->normalizeInterface($interface);
	}
	
	protected function requestData($interface, $data, $method = 'get'){
		$nonce = $this->genNonce();
		$params = array(
			'data' => $data,
			'nonce' => $nonce,
			'aid' => $this->getConfig()->getAppId(),
			'token_h' => sha1($this->getConfig()->getStorage()->getAccessToken()->getAccessToken()),
			'h' => $this->genHash($data, $nonce),
		);
		$url = $this->genInterfaceUrl($interface);
		$ch = null;
		switch(strtolower($method)){
			case 'get':
				$requestUrl = $url .'?'.http_build_query($params);
				$ch = curl_init($requestUrl);
				break;
			case 'post':
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
				break;
			case 'put':
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
				break;
			case 'delete':
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
				break;
		}
		
		if(!is_resource($ch) || get_resource_type($ch) !== 'curl'){
			return array("statuscode" => 405, "content" => 'Method not allowed');
		}
		
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$res = array("statuscode" => $httpcode, "content" => $output);

		return $res;
	}
	
	public function getData($interface, $data){
		return $this->requestData($interface, $data);
	}
	
	public function postData($interface, $data){
		return $this->requestData($interface, $data, 'post');
	}
	
	public function putData($interface, $data){
		return $this->requestData($interface, $data, 'put');
	}
	
	public function deleteData($interface, $data){
		return $this->requestData($interface, $data, 'delete');
	}
}
