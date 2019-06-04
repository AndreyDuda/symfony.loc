<?php


namespace App\Model\User\Entity\User;


use Webmozart\Assert\Assert;

class Name
{
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $first;
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $last;
	public function __construct(string $first, string $last)
	{
		Assert::notEmpty($first);
		Assert::notEmpty($last);
		$this->first = $first;
		$this->last = $last;
	}
	public function getFirst(): string
	{
		return $this->first;
	}
	public function getLast(): string
	{
		return $this->last;
	}
	public function getFull(): string
	{
		return $this->first . ' ' . $this->last;
	}
}