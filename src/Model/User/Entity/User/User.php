<?php

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
	private const STATUS_WAIT   = 'wait';
	private const STATUS_ACTIVE = 'active';
	private const STATUS_NEW = 'new';
	
	/**
	 * @var Id
	 */
	private $id;
	
	/**
	 * @var \DateTimeImmutable
	 */
	private $date;
	/**
	 * @var Email
	 */
	private $email;
	
	/**
	 * @var string
	 */
	private $passwordHash;
	
	/**
	 * @var string|null
	 */
	private $confirmToken;
	
	/**
	 * @var string
	 */
	private $status;
	
	/**
	 * @var Network[]|ArrayCollection
	 */
	private $network;
	
	/**
	 * @var string
	 */
	private $indentity;
	
	public function __construct(Id $id, \DateTimeImmutable $date)
	{
		$this->id = $id;
		$this->date = $date;
		$this->status = self::STATUS_NEW;
		$this->network = new ArrayCollection();
	}
	
	public function signUpByEmail(Email $email, string $hash, string $token): void
	{
		if (!$this->isNew()) {
			throw new \DomainException('User is already signed up.');
		}
		
		$this->email = $email;
		$this->passwordHash = $hash;
		$this->confirmToken = $token;
		$this->status = self::STATUS_WAIT;
	}
	
	public function confirmSignUp(): void
	{
		if (!$this->isWait()) {
			throw new \DomainException('User is already confirmed.');
		}
		$this->status = self::STATUS_ACTIVE;
		$this->confirmToken = null;
	}
	
	public function signUpByNetwork(string $network, string $identity): void
	{
		if (!$this->isNew()) {
			throw new \DomainException('User is already signed up.');
		}
		
		$this->network->add(new Network($this, $network, $identity));
		$this->status = self::STATUS_ACTIVE;
	}
	
	public function attachNetwork(string $network, string $identity)
	{
		foreach ($this->network as $existing) {
			if ($existing->isForNetwork($network)) {
				throw new \DomainException('Network is already attached');
			}
			$this->add(new Network($this, $network, $identity));
		}
	}
	
	public function getId(): Id
	{
		return $this->id;
	}
	
	public function getDate(): \DateTimeImmutable
	{
		return $this->date;
	}
	
	public function getEmail(): Email
	{
		return $this->email;
	}
	
	public function getPasswordHash(): string
	{
		return $this->passwordHash;
	}
	
	public function getConfirmToken()
	{
		return $this->confirmToken;
	}
	
	/**
	 * @return Network[]
	 */
	public function getNetwork(): array
	{
		return $this->network->toArray();
	}
	
	public function isWait()
	{
		return $this->status === self::STATUS_WAIT;
	}
	
	public function isActive()
	{
		return $this->status === self::STATUS_ACTIVE;
	}
	
	public function isNew()
	{
		return $this->status == self::STATUS_NEW;
	}
}