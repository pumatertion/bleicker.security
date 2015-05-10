<?php
namespace Bleicker\Security;

use Doctrine\Common\Collections\Collection;

/**
 * Class SecurityManager
 *
 * @package Bleicker\Security
 */
interface SecurityManagerInterface {

	/**
	 * @param string $subject
	 */
	public function vote($subject);

	/**
	 * @return Collection
	 */
	public function getResults();
}