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
	 * @return $this
	 */
	public function vote($subject);

	/**
	 * @return Collection
	 */
	public function getResults();
}