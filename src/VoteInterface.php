<?php

namespace Bleicker\Security;

use Bleicker\Security\Exception\AbstractVoterException;
use Bleicker\Security\Exception\InvalidVoterExceptionException;
use Closure;

/**
 * Interface VoteInterface
 *
 * @package Bleicker\Security
 */
interface VoteInterface {

	const DEFAULT_PATTERN = '.*', DEFAULT_MODIFIER = '';

	/**
	 * @throws AbstractVoterException
	 * @throws InvalidVoterExceptionException
	 */
	public function vote();

	/**
	 * @return string
	 */
	public function getPattern();

	/**
	 * @return string
	 */
	public function getModifier();
}
