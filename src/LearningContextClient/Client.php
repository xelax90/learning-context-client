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
 * Description of Client
 *
 * @author schurix
 */
class Client {
	
	/**
	 * @var Config
	 */
	protected $config;
	
	/**
	 * @var TokenManager
	 */
	protected $tokenManager;
	
	public function __construct($config) {
		$this->config = $config;
	}
	
	/**
	 * @return Config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @return TokenManager
	 */
	public function getTokenManager() {
		if(null === $this->tokenManager){
			$this->tokenManager = new TokenManager($this->getConfig());
		}
		return $this->tokenManager;
	}
	
	/**
	* Returns the saved refresh token
	* @param String api_url Url of the Learning Context Api
	* @return String refresh_token
	*/
	public function getRefreshToken() {
		return $this->geetTokenManager()->getRefreshToken();
	}
	
	public function get($interface, $data){
		return $this->request($interface, $data, 'get');
	}
	
	public function post($interface, $data){
		return $this->request($interface, $data, 'post');
	}
	
	public function put($interface, $data){
		return $this->request($interface, $data, 'put');
	}
	
	public function delete($interface, $data){
		return $this->request($interface, $data, 'delete');
	}
	
	protected function getResponse($interface, $data, $method){
		if(!in_array(strtolower($method), array('get', 'post', 'put', 'delete'))){
			return array('content' => 'Method not allowed', 'statuscode' => 405);
		}
		
		$cd = new ContextData($this->getConfig());
		switch(strtolower($method)){
			case 'post' : 
				$result = $cd->postData($interface, $data);
				break;
			case 'put' : 
				$result = $cd->putData($interface, $data);
				break;
			case 'delete' : 
				$result = $cd->deleteData($interface, $data);
				break;
			case 'get' : 
			default:
				$result = $cd->getData($interface, $data);
				break;
		}
		return $result;
	}
	
	protected function request($interface, $data, $method){
		$result = $this->getResponse($interface, $data, $method);
		switch ($result["statuscode"]) {
			case 200: //Request successful
				return $result["content"];
			case 203: //Scopes changed
				return $result["content"];
			case 401: //access token expired
				if($this->getTokenManager()->accessTokenRefresh() == 1) {
					$response = $this->getResponse($interface, $data, $method);
					if($response["statuscode"] != 401) {
						return $response["content"];
					}
				}
				return '{"result":0,"reason":"Unauthorized: access token expired"}';
			case 403: //Wrong data
				return $result["content"];
			case 405: // Wrong method specified
				return '{"result":0,"reason":"Method not allowed"}';
			default:
				return $result["content"];
		}
	}
}
