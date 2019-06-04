<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;

interface ResetTokenSender
{
	public function send(Email $email, ResetToken $token): void;
}