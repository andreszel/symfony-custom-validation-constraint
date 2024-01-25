<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MobilePhoneValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof MobilePhone) {
            throw new UnexpectedTypeException($constraint, MobilePhone::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        // 9 znaków, tylko cfry
        $pattern = '/^([1-9]){1}([0-9]){8}$/';

        if(!preg_match($pattern, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
