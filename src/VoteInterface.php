<?php

namespace Bleicker\Security;

use Closure;

/**
 * Interface VoteInterface
 *
 * @package Bleicker\Security
 */
interface VoteInterface {

	const DEFAULT_PATTERN = '.*', DEFAULT_MODIFIER = '';

	/**
	 * @return void
	 */
	public function run();

	/**
	 * @return string
	 */
	public function getPattern();

	/**
	 * @return string
	 */
	public function getModifier();

	/**
	 * @param string $alias
	 * @param string $pattern
	 * @param string $modifier
	 * @param Closure $closure
	 * @return static
	 */
	public static function register($alias, Closure $closure, $pattern = VoteInterface::DEFAULT_PATTERN, $modifier = VoteInterface::DEFAULT_MODIFIER);
}
