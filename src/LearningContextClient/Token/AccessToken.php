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

namespace LearningContextClient\Token;

/**
 * AccessToken. This object is immutable!
 *
 * @author schurix
 */
class AccessToken extends Token{
	
	/**
	 * Stored access token
	 * @var string
	 */
	protected $accessToken;
	
	public function __construct($issueTime, $accessToken) {
		parent::__construct($issueTime);
		$this->setAccessToken($accessToken);
	}
	
	/**
	 * @return string
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}
	
	/**
	 * @param string $accessToken
	 * @return AccessToken
	 */
	protected function setAccessToken($accessToken) {
		$this->accessToken = $accessToken;
		return $this;
	}

	
	public function jsonSerialize() {
		$data = parent::jsonSerialize();
		return array_merge($data, array(
			'accessToken' => $this->getAccessToken(),
		));
	}
}
