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

use JsonSerializable;
use DateTime;

/**
 * Basi Token. This object is immutable!
 *
 * @author schurix
 */
class Token implements JsonSerializable{
	
	/**
	 * @var DateTime
	 */
	protected $issueTime;
	
	public function __construct(DateTime $issueTime) {
		$this->setIssueTime($issueTime);
	}
	
	/**
	 * @return DateTime
	 */
	public function getIssueTime() {
		return $this->issueTime;
	}

	/**
	 * @param DateTime $issueTime
	 * @return Token
	 */
	protected function setIssueTime($issueTime) {
		$this->issueTime = $issueTime;
		return $this;
	}

	public function jsonSerialize() {
		return array(
			'issueTime' => $this->getIssueTime()->format(DateTime::ATOM),
		);
	}
	
	public function __sleep() {
		return array_keys($this->jsonSerialize());
	}

}
