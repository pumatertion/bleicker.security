<?php

namespace Tests\Bleicker\Security\Unit\Fixtures;

use Bleicker\Token\AbstractPrototypeToken;

/**
 * Class FailingToken
 *
 * @package Tests\Bleicker\Security\Unit\Fixtures
 */
class FailingToken extends AbstractPrototypeToken {

	/**
	 * @return $this
	 */
	public function authenticate() {

		if ($this->getStatus() === self::AUTHENTICATION_SUCCESS || $this->getStatus() !== self::AUTHENTICATION_REQUIRED) {
			return $this;
		}

		if ($this->getCredentials() === 'foo') {
			$this->status = self::AUTHENTICATION_SUCCESS;
			return $this;
		}

		$this->status = self::AUTHENTICATION_FAILED;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function injectCredentialsAndSetStatus() {
		$this->credentials = 'bar';
		$this->status = self::AUTHENTICATION_REQUIRED;
		return $this;
	}
}
