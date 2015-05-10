<?php
namespace Bleicker\Security;

use Bleicker\Container\Exception\AliasAlreadyExistsException;

/**
 * Class Votes
 *
 * @package Bleicker\Security
 */
interface VotesInterface {

	/**
	 * @return array
	 */
	public static function storage();

	/**
	 * @param string $alias
	 * @return boolean
	 */
	public static function has($alias);

	/**
	 * @param string $alias
	 * @param VoteInterface $data
	 * @return static
	 * @throws AliasAlreadyExistsException
	 */
	public static function add($alias, $data);

	/**
	 * @return static
	 */
	public static function prune();

	/**
	 * @param string $alias
	 * @return VoteInterface
	 */
	public static function get($alias);

	/**
	 * @param string $alias
	 * @return static
	 */
	public static function remove($alias);
}