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

namespace LearningContextClient\Storage;

use LearningContextClient\Token\RefreshToken;
use LearningContextClient\Token\AccessToken;
use DateTime;

/**
 * Implementation of StorageInterface for simple Session Array storage. Requires the session to be started before usage.
 *
 * @author schurix
 */
class SessionArrayStorage implements StorageInterface{
	protected $accessTokenKey = 'lctx_access_token';
	protected $refreshTokenKey = 'lctx_refresh_token';
	
	protected $accessTokenInstance = null;
	protected $refreshTokenInstance = null;

	public function __construct($accessTokenKey = null, $refreshTokenKey = null) {
		if($accessTokenKey !== null){
			$this->accessTokenKey = $accessTokenKey;
		}
		if($refreshTokenKey !== null){
			$this->refreshTokenKey = $refreshTokenKey;
		}
	}
	
	protected function syncRefreshToken(RefreshToken $token = null){
		if($token !== null){
			$this->refreshTokenInstance = $token;
			$_SESSION[$this->refreshTokenKey] = array('issueTime' => $token->getIssueTime()->format(DateTime::ATOM), 'refreshToken' => $token->getRefreshToken());
		} else {
			$tk = $_SESSION[$this->refreshTokenKey];
			if($tk === null){
				$this->refreshTokenInstance = null;
			} elseif ($this->refreshTokenInstance === null){
				$this->refreshTokenInstance = new RefreshToken(DateTime::createFromFormat(DateTime::ATOM, $tk['issueTime']), $tk['refreshToken']);
			}
		}
		return $this->refreshTokenInstance;
	}
	
	protected function syncAccessToken(AccessToken $token = null){
		if($token !== null){
			$this->accessTokenInstance = $token;
			$_SESSION[$this->accessTokenKey] = array('issueTime' => $token->getIssueTime()->format(DateTime::ATOM), 'accessToken' => $token->getAccessToken());
		} else {
			$tk = $_SESSION[$this->accessTokenKey];
			if($tk === null){
				$this->accessTokenInstance = null;
			} elseif ($this->accessTokenInstance === null){
				$this->accessTokenInstance = new AccessToken(DateTime::createFromFormat(DateTime::ATOM, $tk['issueTime']), $tk['accessToken']);
			}
		}
		return $this->accessTokenInstance;
	}
	
	public function deleteAccessToken() {
		$_SESSION[$this->accessTokenKey] = null;
		$this->syncAccessToken();
	}

	public function deleteRefreshToken() {
		$_SESSION[$this->refreshTokenKey] = null;
		$this->syncRefreshToken();
	}

	public function getAccessToken() {
		return $this->syncAccessToken();
	}

	public function getRefreshToken() {
		return $this->syncRefreshToken();
	}

	public function saveAccessToken(AccessToken $accessToken) {
		$this->syncAccessToken($accessToken);
	}

	public function saveRefreshToken(RefreshToken $refreshToken) {
		$this->syncRefreshToken($refreshToken);
	}
}
