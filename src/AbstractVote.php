<?php

namespace Bleicker\Security;

use Bleicker\ObjectManager\ObjectManager;
use Closure;
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
	public function __construct(Closure $vote, $pattern = VoteInterface::DEFAULT_PATTERN, $modifier = VoteInterface::DEFAULT_MODIFIER) {
		$this->vote = $vote;
		$this->pattern = $pattern;
		$this->modifier = $modifier;
	}

	/**
	 * @return void
	 */
	public function run() {
		call_user_func($this->vote);
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
	 * @param string $alias
	 * @param string $pattern
	 * @param string $modifier
	 * @param Closure $closure
	 * @return static
	 */
	public static function register($alias, Closure $closure, $pattern = VoteInterface::DEFAULT_PATTERN, $modifier = VoteInterface::DEFAULT_MODIFIER) {
		$reflection = new ReflectionClass(static::class);
		$constructorArguments = array_values(array_slice(func_get_args(), 1));
		/** @var static $instance */
		$instance = $reflection->newInstanceArgs($constructorArguments);
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
