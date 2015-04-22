<?php

namespace Bleicker\Security;

use Bleicker\Security\Exception\AbstractVoterException;
use Bleicker\Security\Exception\InvalidVoterExceptionException;
use Closure;
use Exception;

/**
 * Class Vote
 *
 * @package Bleicker\Security
 */
class Vote implements VoteInterface {

	/**
	 * @var string
	 */
	protected $pattern;

	/**
	 * @var string
	 */
	protected $modifier;

	/**
	 * @var Closure
	 */
	protected $closure;

	/**
	 * @param callable $closure
	 * @param string $pattern
	 * @param string $modifier
	 */
	public function __construct(Closure $closure, $pattern = self::DEFAULT_PATTERN, $modifier = self::DEFAULT_MODIFIER) {
		$this->pattern = $pattern;
		$this->closure = $closure;
		$this->modifier = $modifier;
	}

	/**
	 * @return string
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * @return string
	 */
	public function getModifier() {
		return $this->modifier;
	}

	/**
	 * @throws InvalidVoterExceptionException
	 * @throws AbstractVoterException
	 */
	public function vote() {
		try {
			call_user_func_array($this->closure, func_get_args());
		} catch (AbstractVoterException $exception) {
			throw new $exception;
		} catch (Exception $exception) {
			throw new InvalidVoterExceptionException('Closure throws invalid Exception. Only implementation of AbstractVoterException allowed.', 1429725934);
		}
	}
}


