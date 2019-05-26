<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SingUp;

use App\Model\User\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RequestTest extends TestCase
{
	public function testSuccess(): void
	{
		$user = new User(
			$email = 'test@app.test',
			$hash  = 'hash'
		);
		
		self::assertEquals($email, $user->getEmail());
		self::assertEquals($email, $user->getPasswordHash());
	}
}