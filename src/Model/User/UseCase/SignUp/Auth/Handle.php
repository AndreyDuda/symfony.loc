<?php

namespace App\Model\User\UseCase\SignUp\Auth;

use App\Model\Flusher;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;

class Handle
{
	private $users;
	private $flusher;
	
	public function __construct(UserRepository $users, Flusher $flusher)
	{
		$this->users = $users;
		$this->flusher = $flusher;
	}
	
	public function handle(Command $command): void
	{
		if ($this->users->hasByNetworkIndetity($command->network, $command->identity)) {
			throw  new \DomainException('User already exists.');
		}
		
		$user = new User(
			Id::next(),
			new \DateTimeImmutable()
		);
		
		$user->signUpByNetwork(
			$command->network,
			$command->identity
		);
		
		$this->users->add($user);
		
		$this->flusher->flush();
	}
}