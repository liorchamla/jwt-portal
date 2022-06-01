<?php

namespace App\Http\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintsViolationsException extends Exception
{
    public function __construct(public ConstraintViolationListInterface $violations)
    {
    }
}
