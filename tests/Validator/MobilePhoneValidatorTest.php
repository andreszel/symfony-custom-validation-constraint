<?php

namespace App\Tests\Validator;

use App\Validator\MobilePhone;
use App\Validator\MobilePhoneValidator;
use App\Validator\StrongPassword;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class MobilePhoneValidatorTest extends ConstraintValidatorTestCase
{
    public function createValidator(): MobilePhoneValidator
    {
        return new MobilePhoneValidator();
    }

    public function provideIsValid(): iterable
    {
        yield ['605605605'];
        yield ['501502503'];
        yield ['511611900'];
        yield ['721777232'];
    }

    /**
     * @dataProvider provideIsValid
     */
    public function testIsValid(?string $value): void
    {
        $this->validator->validate($value, new MobilePhone());
        $this->assertNoViolation();
    }

    public function testIsInvalid(): void
    {
        $value = '432';
        $this->validator->validate($value, new MobilePhone());
        $this->buildViolation('The value "{{ value }}" is not valid.')
            ->setParameter('{{ value }}', $value)
            ->assertRaised();
    }

    public function testInvalidValueType(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate('605111222', new StrongPassword());
    }
}
