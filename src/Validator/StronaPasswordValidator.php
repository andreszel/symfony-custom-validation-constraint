<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StronaPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof StronaPassword) {
            throw new UnexpectedTypeException($constraint, StronaPassword::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if(!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        // min 8 znaków, przynajmniej 1 wielka, 1 mała, 1 cyfra i 1 znak specjalny
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

        if(!preg_match($pattern, $value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
        }
    }
}
