<?php

namespace App\Model\User\Entity\User;

use App\Model\User\Service\ResetToken;
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
	 * @var string|null
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
	 * @var ResetToken|null
	 */
	private $resetToken;
	
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
	
	public function requestPasswordReset(ResetToken $token, \DateTimeImmutable $date): void
	{
		if (!$this->isActive()) {
			throw new \DomainException('User is nor active.');
		}
		if (!$this->email) {
			throw new \DomainException('Email is not specified.');
		}
		if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
			throw new \DomainException('Resetting is already requested.');
		}
		
		$this->resetToken = $token;
	}
	
	public function passwordReset(\DateTimeImmutable $date, string $hash): void
	{
		if (!$this->resetToken) {
			throw new \DomainException('Reset is not requested.');
		}
		if ($this->resetToken->isExpiredTo($date)) {
			throw new \DomainException('Reset token is expired.');
		}
		$this->passwordHash = $hash;
	}
	
}