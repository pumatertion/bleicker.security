<?php

namespace Bleicker\Security;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Security\Exception\AbstractVoterException;
use Bleicker\Security\Exception\InvalidVoterExceptionException;
use Closure;
use Exception;
use ReflectionClass;

/**
 * Class AbstractVote
 *
 * @package Bleicker\Security
 */
abstract class AbstractVote implements VoteInterface {

	/**
	 * @var Closure
	 */
	protected $vote;

	/**
	 * @var string
	 */
	protected $pattern;

	/**
	 * @var string
	 */
	protected $modifier;

	/**
	 * @param Closure $vote
	 * @param string $pattern
	 * @param string $modifier
	 */
	public final function __construct(Closure $vote, $pattern, $modifier) {
		$this->vote = $vote;
		$this->pattern = $pattern;
		$this->modifier = $modifier;
	}

	/**
	 * @throws AbstractVoterException
	 * @throws InvalidVoterExceptionException
	 * @return void
	 */
	public final function vote() {
		try {
			call_user_func($this->vote);
		} catch (AbstractVoterException $exception) {
			throw $exception;
		} catch (Exception $invalidException) {
			throw new InvalidVoterExceptionException('Closure must throw an in instance of "' . AbstractVoterException::class . '"', 1431266583);
		}
	}

	/**
	 * @return string
	 */
	public final function getPattern() {
		return $this->pattern;
	}

	/**
	 * @return string
	 */
	public final function getModifier() {
		return $this->modifier;
	}

	/**
	 * @param string $alias
	 * @param string $pattern
	 * @param string $modifier
	 * @param Closure $closure
	 * @return static
	 */
	public static final function register($alias, Closure $closure, $pattern = VoteInterface::DEFAULT_PATTERN, $modifier = VoteInterface::DEFAULT_MODIFIER) {
		$reflection = new ReflectionClass(static::class);
		/** @var static $instance */
		$instance = $reflection->newInstanceArgs(array_slice(func_get_args(), 1));
		/** @var VotesInterface $votes */
		$votes = ObjectManager::get(VotesInterface::class, function () {
			$votes = new Votes();
			ObjectManager::add(VotesInterface::class, $votes, TRUE);
			return $votes;
		});
		$votes->add($alias, $instance);
		return $instance;
	}
}
