<?php

namespace App\Users\Dto;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class Registration
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Email()]
        public string $email = '',

        #[Assert\NotBlank()]
        public string $password = ''
    ) {
    }

    public function toEntity(): User
    {
        return (new User)->setEmail($this->email)
            ->setPlainPassword($this->password);
    }
}
