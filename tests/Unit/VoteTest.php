<?php

namespace Tests\Bleicker\Security\Unit;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Security\Vote;
use Bleicker\Security\Exception\AccessDeniedException;
use Bleicker\Session\Session;
use Bleicker\Session\SessionInterface;
use Bleicker\Token\TokenManager;
use Bleicker\Token\TokenManagerInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Tests\Bleicker\Security\Unit\Fixtures\AuthenticationFailedException;
use Tests\Bleicker\Security\UnitTestCase;
use Bleicker\Authentication\AuthenticationManagerInterface;
use Bleicker\Authentication\AuthenticationManager;
use Tests\Bleicker\Security\Unit\Fixtures\FailingToken;

/**
 * Class RegistryTest
 *
 * @package Tests\Bleicker\Security\Unit
 */
class VoteTest extends UnitTestCase {

	/**
	 * @var AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	protected function setUp() {
		parent::setUp();
		ObjectManager::register(SessionInterface::class, new Session(new MockArraySessionStorage()));
		ObjectManager::register(TokenManagerInterface::class, new TokenManager());
		ObjectManager::register(AuthenticationManagerInterface::class, new AuthenticationManager());

		/** @var AuthenticationManagerInterface $authenticationManager */
		$authenticationManager = ObjectManager::get(AuthenticationManagerInterface::class);
		$authenticationManager->getTokenManager()->registerPrototypeToken(FailingToken::class, new FailingToken());
		$authenticationManager->run();

		$this->authenticationManager = $authenticationManager;
	}

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
	 * @expectedException \Tests\Bleicker\Security\Unit\Fixtures\AuthenticationFailedException
	 */
	public function voteByAuthenticationManagerTest(){
		$voter = new Vote(function () {
			if($this->authenticationManager->getTokenManager()->getPrototypeToken(FailingToken::class)->getStatus() === FailingToken::AUTHENTICATION_FAILED){
				throw new AuthenticationFailedException('Authentication Failed');
			}
		});
		$voter->vote();
	}
}
