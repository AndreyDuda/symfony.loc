<?php


namespace App\Model\User\Service;


use Ramsey\Uuid\Uuid;

class ResetTokenizer
{
	private $interval;
	
	public function __construct(\DateTimeImmutable $interval)
	{
		$this->interval = $interval;
	}
	
	public function generate(): ResetToken
	{
		return new ResetToken(
		Uuid::uuid4()->toString(),
			(new \DateTimeImmutable())->add($this->interval)
		);
	}
}