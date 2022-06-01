<?php

namespace App\Accounts\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Credentials
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Email()]
        public string $email = '',

        #[Assert\NotBlank()]
        public string $password = ''
    ) {
    }
}
