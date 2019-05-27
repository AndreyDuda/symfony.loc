<?php

namespace App\Model\User\UseCase\SignUp\Confirm;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserRepository;

class Handler
{
	private $users;
	private $flusher;
	
	public function __construct(UserRepository $users, Flusher $flusher)
	{
		$this->users = $users;
		$this->flusher = $flusher;
	}
}