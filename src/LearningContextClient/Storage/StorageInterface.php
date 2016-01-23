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

use LearningContextClient\Token\AccessToken;
use LearningContextClient\Token\RefreshToken;

/**
 *
 * @author schurix
 */
interface StorageInterface {
	
	public function saveRefreshToken(RefreshToken $refreshToken);
	public function deleteRefreshToken();
	/**
	 * @return RefreshToken|null
	 */
	public function getRefreshToken();
	
	public function saveAccessToken(AccessToken $accessToken);
	public function deleteAccessToken();
	/**
	 * @return AccessToken|null
	 */
	public function getAccessToken();
	
}
