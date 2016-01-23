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

use LearningContextClient\Token\RefreshToken;
use DateTime;

/**
 * Description of TokenManager
 *
 * @author schurix
 */
class TokenManager {
	protected $config;
	
	/**
	 * Creates token manager and requests new access token if necessary.
	 * Also redirects to oauth login if no RefreshToken is saved
	 * @param Config $config
	 * @param string|RefreshToken $refreshToken
	 */
	public function __construct(Config $config, $refreshToken = null) {
		$this->config = $config;
		
		if($this->getConfig()->getStorage()->getRefreshToken() === null || $refreshToken !== null){
			if ($refreshToken !== null){
				// if refreshToken parameter was provided, store that RefreshToken
				$token = null;
				if($refreshToken instanceof RefreshToken){
					$token = $refreshToken;
				} else {
					$token =  new RefreshToken(new DateTime(), $refreshToken);
				}
				$this->getConfig()->getStorage()->saveRefreshToken($token);
			} elseif(!empty($_REQUEST['rt'])) {
				// if the rt key was sent, use it
				$this->getConfig()->getStorage()->saveRefreshToken(new RefreshToken(new DateTime(), $_REQUEST['rt']));
			} else {
				// if no refresh token provided, request redirect to oauth login to request a new one.
				$params = array(
					'id' => $this->getConfig()->getAppId(),
					'url' => $this->getConfig()->getCallbackUrl(),
					'hash' => hash('sha512', $this->getConfig()->getAppId().$this->getConfig()->getAppSecret().$this->getConfig()->getCallbackUrl()),
				);
				$url = $this->getConfig()->getOAuthServer().'/login?'.  http_build_query($params);
				var_dump($url);
				//header("Location: $url");
				die();
			}
		}
		
		$this->accessTokenRefresh();
	}
	
	public function getConfig() {
		return $this->config;
	}
	
	/**
	 * @return Token\AccessToken
	 */
	public function getAccessToken(){
		return $this->getConfig()->getStorage()->getAccessToken();
	}
	
	public function getRefreshToken(){
		return $this->getConfig()->getStorage()->getRefreshToken();
	}
	
	public function accessTokenRefresh(){
		$params = array(
			'app_id' => $this->getConfig()->getAppId(),
			'hash' => hash('sha512', $this->getConfig()->getAppId().$this->getConfig()->getAppSecret().$this->getConfig()->getStorage()->getRefreshToken()->getRefreshToken()),
		);
		$url = $this->getConfig()->getOAuthServer()."/refresh_token?" . http_build_query($params);
		var_dump($url);
		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		
		$fp = fopen('public/test.txt', "w+");
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_STDERR, $fp);
		
		$output = curl_exec($ch);
		fclose($fp);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$res = array("statuscode" => $httpcode, "content" => $output);
		echo 'A';
		var_dump($res);
		switch($res["statuscode"]) {
			case 200: //Refresh successful
				$array = json_decode($res["content"]);
				var_dump($array);
				$this->getConfig()->getStorage()->saveAccessToken(new Token\AccessToken(new DateTime(), $array->token));
				return 1;
			case 400: //Wrong app_id
				return 0;					
			case 401: //Refresh token expired
				$params2 = array(
					'id' => $this->getConfig()->getAppId(),
					'url' => $this->getConfig()->getCallbackUrl(),
					'hash' => hash('sha512', $this->getConfig()->getAppId().$this->getConfig()->getAppSecret().$this->getConfig()->getCallbackUrl()),
				);
				$url2 = $this->getConfig()->getOAuthServer().'/login?'.  http_build_query($params2);
				var_dump($url2);
				//header("Location: $url2");
				die();
			default:
				return 0;
		}
	}
}
