<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SingUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RequestTest extends TestCase
{
	public function testSuccess(): void
	{
		$user = new User(
			$id = Id::next(),
			$date = new \DateTimeImmutable(),
			$email = new Email('test@app.test'),
			$hash  = 'hash',
			$token = 'token'
		);
		
		self::assertTrue($user->isWait());
		self::assertFalse($user->isActive());
		
		self::assertEquals($id, $user->getId());
		self::assertEquals($date, $user->getDate());
		self::assertEquals($email, $user->getEmail());
		self::assertEquals($hash, $user->getPasswordHash());
		self::assertEquals($token, $user->getConfirmToken());
	}
}