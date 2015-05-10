<?php

namespace Bleicker\Security;

use Bleicker\ObjectManager\ObjectManager;
use Bleicker\Security\Exception\AbstractVoteException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class SecurityManager
 *
 * @package Bleicker\Security
 */
class SecurityManager implements SecurityManagerInterface {

	/**
	 * @var VotesInterface
	 */
	protected $votes;

	/**
	 * @var Collection
	 */
	protected $results;

	public function __construct() {
		$this->results = new ArrayCollection();
		$this->votes = ObjectManager::get(VotesInterface::class, function () {
			$votes = new Votes();
			ObjectManager::add(VotesInterface::class, $votes, TRUE);
			return $votes;
		});
	}

	/**
	 * @param string $subject
	 */
	public function vote($subject) {
		$this->results->clear();
		$matchingVotes = $this->getMatchingVotes($subject);
		/** @var VoteInterface $vote */
		while ($vote = $matchingVotes->current()) {
			try {
				$vote->run();
			} catch (AbstractVoteException $exception) {
				$this->results->add($exception);
			}
			$matchingVotes->next();
		}
	}

	/**
	 * @return Collection
	 */
	public function getResults() {
		return $this->results;
	}

	protected function getMatchingVotes($subject) {
		$votes = new ArrayCollection($this->votes->storage());
		return $votes->filter($this->votesWherePatternMatchingSubjectFilter($subject));
	}

	/**
	 * @param string $subject
	 * @return callable
	 */

	protected static function votesWherePatternMatchingSubjectFilter($subject) {
		return function (VoteInterface $vote) use ($subject) {
			return (boolean)preg_match('<' . addslashes($vote->getPattern()) . '>' . addslashes($vote->getModifier()), $subject);
		};
	}
}
