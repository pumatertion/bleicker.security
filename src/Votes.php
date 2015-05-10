<?php

namespace Bleicker\Security;

use Bleicker\Container\AbstractContainer;

/**
 * Class Votes
 *
 * @package Bleicker\Security
 */
class Votes extends AbstractContainer implements VotesInterface {

	/**
	 * @param string $alias
	 * @return VoteInterface
	 */
	public static function get($alias) {
		return parent::get($alias);
	}

	/**
	 * @param string $alias
	 * @param VoteInterface $data
	 * @return static
	 */
	public static function add($alias, $data) {
		return parent::add($alias, $data);
	}
}
