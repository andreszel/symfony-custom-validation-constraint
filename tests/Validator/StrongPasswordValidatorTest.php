<?php

namespace App\Tests\Validator;

use App\Validator\MobilePhone;
use App\Validator\StrongPassword;
use App\Validator\StrongPasswordValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class StrongPasswordValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): StrongPasswordValidator
    {
        return new StrongPasswordValidator();
    }

    public function provideIsValid(): iterable
    {
        yield ['Foobar1234!'];
        yield ['!testAA99s'];
    }

    /**
     * @dataProvider provideIsValid
     */
    public function testIsValid(?string $value): void
    {
        $this->validator->validate($value, new StrongPassword());
        $this->assertNoViolation();
    }

    public function testIsInvalid(): void
    {
        $value = 'StrongPassword';
        $this->validator->validate($value, new StrongPassword());
        $this->buildViolation('The value "{{ value }}" is not valid.')
            ->setParameter('{{ value }}', $value)
            ->assertRaised();
    }

    public function testInvalidValueType(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate('test', new MobilePhone());
    }

    public function testInvalidConstraintType(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('string');
        $this->validator->validate(33, new StrongPassword());
    }
}
