<?php


namespace App\Tests\Unit\Model\User\Entity\User\SingUp;


use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ConfirmTest extends TestCase
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
		$user = new User(
			Id::next(),
			new \DateTimeImmutable()
		);
		
		return $user->signUpByEmail(
			$email = new Email('test@app.test'),
			$hash  = 'hash',
			$token = 'token'
		);
	}
}