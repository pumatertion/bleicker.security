<?php

namespace Tests\Bleicker\Security\Unit;

use Bleicker\Security\Vote;
use Bleicker\Security\Exception\AccessDeniedException;
use Bleicker\Security\Exception\AccessGrantedException;
use Tests\Bleicker\Security\UnitTestCase;

/**
 * Class RegistryTest
 *
 * @package Tests\Bleicker\Security\Unit
 */
class VoteTest extends UnitTestCase {

	/**
	 * @test
	 */
	public function hasPatternTest() {
		$voter = new Vote(function () {
			throw new AccessDeniedException();
		}, self::class);
		$this->assertEquals(self::class, $voter->getPattern());
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Security\Exception\AccessGrantedException
	 */
	public function grantedTest() {
		$voter = new Vote(function () {
			throw new AccessGrantedException();
		});
		$voter->vote();
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Security\Exception\AccessDeniedException
	 */
	public function deniedTest() {
		$voter = new Vote(function () {
			throw new AccessDeniedException();
		});
		$voter->vote();
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Security\Exception\InvalidVoterExceptionException
	 */
	public function unknowExceptionTest() {
		$voter = new Vote(function () {
			throw new \Exception();
		});
		$voter->vote();
	}

	/**
	 * @test
	 * @expectedException \Bleicker\Security\Exception\MissingVoterExceptionException
	 */
	public function noExceptionTest() {
		$voter = new Vote(function () {
		});
		$voter->vote();
	}
}
