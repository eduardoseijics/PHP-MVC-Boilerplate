<?php

namespace App\Domain\User\Entity;

use App\Domain\Shared\ValueObject\Email;
use App\Domain\User\Entity\ValueObject\UserName;
use App\Domain\User\Entity\ValueObject\UserPassword;

class User
{
  public function __construct(
    private UserName $name,
    private Email $email,
    private UserPassword $password
  ){}
}
