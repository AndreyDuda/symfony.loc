<?php

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class Email
{
	/**
	 * @var string
	 */
	private $value;
	
	public function __construct(string $value)
	{
		Assert::notEmpty($value);
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			throw new \InvalidArgumentException('Incorrect email.');
		}
		$this->value = mb_strtolower($value);
	}
	
	public function getValue()
	{
		return $this->value;
	}
}