<?php

namespace Tests\Bleicker\Token\Unit;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Security\SecurityManager;
use Bleicker\Security\SecurityManagerInterface;
use Bleicker\Security\Vote;
use Bleicker\Security\Votes;
use Bleicker\Token\Tokens;
use Tests\Bleicker\Security\Unit\Fixtures\Exception\AccessDeniedException;
use Tests\Bleicker\Security\UnitTestCase;

/**
 * Class SecurityManagerTest
 *
 * @package Tests\Bleicker\Token\Unit
 */
class SecurityManagerTest extends UnitTestCase {

	/**
	 * @var SecurityManagerInterface
	 */
	protected $securityManager;

	protected function setUp() {
		parent::setUp();
		$this->securityManager = ObjectManager::get(SecurityManagerInterface::class, function () {
			$securityManager = new SecurityManager();
			ObjectManager::add(SecurityManagerInterface::class, $securityManager, TRUE);
			return $securityManager;
		});
		Tokens::prune();
		Votes::prune();
	}

	protected function tearDown() {
		parent::tearDown();
		Tokens::prune();
		Votes::prune();
	}

	/**
	 * @test
	 */
	public function controllerMethodPatternDeniesAccessTest() {
		Vote::register('accessVote', function () {
			throw new AccessDeniedException;
		}, '\\Foo\\Bar\\BazController::.*()');
		$this->securityManager->vote('\\Foo\\Bar\\BazController::indexAction()');
		$this->assertEquals(1, $this->securityManager->getResults()->count());
	}

	/**
	 * @test
	 */
	public function controllerMethodPatternGrantsAccessTest() {
		Vote::register('accessVote', function () {
		}, '\\Foo\\Bar\\BazController::.*()');
		$this->securityManager->vote('\\Foo\\Bar\\BazController::indexAction()');
		$this->assertEquals(0, $this->securityManager->getResults()->count());
	}

	/**
	 * @test
	 */
	public function controllerMethodPatternAbstainsIfPatternDoesNotMatchTest() {
		Vote::register('accessVote', function () {
			throw new AccessDeniedException;
		}, '\\Foo\\Bar\\BazController::.*()');
		$this->securityManager->vote('\\Foo\\Baz\\BazController::indexAction()');
		$this->assertEquals(0, $this->securityManager->getResults()->count());
	}

	/**
	 * @test
	 */
	public function multipleControllerMethodPatternDeniesAccessTest() {
		Vote::register('accessVote1', function () {
			throw new AccessDeniedException;
		}, '\\Foo\\Bar\\BazController::.*()');
		Vote::register('accessVote2', function () {
			throw new AccessDeniedException;
		}, '\\Foo\\Bar\\BazController::.*()');
		$this->securityManager->vote('\\Foo\\Bar\\BazController::indexAction()');
		$this->assertEquals(2, $this->securityManager->getResults()->count());
	}
}
