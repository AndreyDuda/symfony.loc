<?php

namespace App\Model\User\Service;

use Ramsey\Uuid\Uuid;

class ConfirmTokenizer
{
	public function generate()
	{
		return Uuid::uuid4()->toString();
	}
}