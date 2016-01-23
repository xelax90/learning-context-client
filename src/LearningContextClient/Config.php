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

use LearningContextClient\Storage\StorageInterface;

/**
 * Configuration for the Learning Context API Client. This object is immutable!
 *
 * @author schurix
 */
class Config {
	
	/**
	 * Learning context API URL
	 * @var string
	 */
	protected $apiUrl = 'http://api.learning-context.de';
	
	/**
	 * Learning Context API Version
	 * @var int
	 */
	protected $apiVersion = 4;
	
	/**
	 * Your APP-ID (see API Keys on the Learning Context page)
	 * @var int
	 */
	protected $appId;
	
	/**
	 * Your APP-Secret (See API Keys on the Learning Context page)
	 * @var string
	 */
	protected $appSecret;
	
	/**
	 * This URL will be called when the user allows your app to access his data
	 * It will recieve a refresh token as 'rt' parameter in $_REQUEST
	 * @var string
	 */
	protected $callbackUrl;
	
	/**
	 * Learning Context OAuth Endpoint
	 * @var string
	 */
	protected $oAuthServer = 'http://www.learning-context.de/oauth';
	
	/**
	 * Storage that saves the tokens
	 * @var StorageInterface
	 */
	protected $storage;
	
	public function __construct($appId, $appSecret, $callbackUrl, StorageInterface $storage, $apiUrl = null, $apiVersion = null, $oauthServer = null) {
		$this->setAppId($appId);
		$this->setAppSecret($appSecret);
		$this->setCallbackUrl($callbackUrl);
		$this->setStorage($storage);
		if($apiUrl !== null){
			$this->setApiUrl($apiUrl);
		}
		if($apiVersion !== null){
			$this->setApiVersion($apiVersion);
		}
		if($oauthServer !== null){
			$this->setOAuthServer($oAuthServer);
		}
	}

	
	public function getApiUrl() {
		return $this->apiUrl;
	}

	public function getApiVersion() {
		return $this->apiVersion;
	}

	public function getAppId() {
		return $this->appId;
	}

	public function getAppSecret() {
		return $this->appSecret;
	}

	public function getCallbackUrl() {
		return $this->callbackUrl;
	}

	public function getOAuthServer() {
		return $this->oAuthServer;
	}

	protected function setApiUrl($apiUrl) {
		$this->apiUrl = rtrim($apiUrl, '/');
		return $this;
	}

	protected function setApiVersion($apiVersion) {
		$this->apiVersion = $apiVersion;
		return $this;
	}

	protected function setAppId($appId) {
		$this->appId = $appId;
		return $this;
	}

	protected function setAppSecret($appSecret) {
		$this->appSecret = $appSecret;
		return $this;
	}

	protected function setCallbackUrl($callbackUrl) {
		$this->callbackUrl = $callbackUrl;
		return $this;
	}

	protected function setOAuthServer($oAuthServer) {
		$this->oAuthServer = rtrim($oAuthServer, '/');
		return $this;
	}
	
	/**
	 * @return StorageInterface
	 */
	public function getStorage() {
		return $this->storage;
	}

	protected function setStorage(StorageInterface $storage) {
		$this->storage = $storage;
		return $this;
	}

}
