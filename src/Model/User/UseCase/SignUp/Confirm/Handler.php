<?php

namespace App\Model\User\UseCase\SignUp\Confirm;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserRepository;

class Handler
{
	/**
	 * @var UserRepository
	 */
	private $users;
	private $flusher;
	
	public function __construct(UserRepository $users, Flusher $flusher)
	{
		$this->users = $users;
		$this->flusher = $flusher;
	}
	
	public function handler(Command $command): void
	{
		if (!$user = $this->users->findByConfrmToken($command->token)) {
			throw new \DomainException('Incorrect or confirm token.');
		}
		
		$user->confirmSignUp();
		$this->flusher->flush();
	}
	
}