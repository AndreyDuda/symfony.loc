<?php


namespace App\Tests\Unit\Model\User\Entity\User\SingUp;


use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;

class ConfirmTest
{
	public function testSuccess(): void
	{
		$user = $this->buildSignedUpUser();
		
		$user->confirmSingUp();
		
		self::assertFalse($user->isWait());
		self::assertTrue($user->isActive());
		
		self::assertNull($user->getConfirmToken());
	}
	
	public function testAlready(): void
	{
		$user = $this->buildSignedUpUser();
		
		$user->confirmSignUp();
		$user->expectExceptionMessage('User is already confirmed');
		$user->confirmSignUp();
	}
	
	public function buildSignedUpUser(): User
	{
		return new User(
			Id::next(),
			new \DateTimeImmutable(),
			new Email('test@app.test'),
			'hash',
			$token = 'token'
		);
	}
}