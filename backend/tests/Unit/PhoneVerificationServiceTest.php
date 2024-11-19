<?php

declare(strict_types=1);

namespace Test\Unit;

use App\Services\PhoneVerificationService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversNothing()]
class PhoneVerificationServiceTest extends TestCase
{
    private PhoneVerificationService $phoneVerificationService;

    protected function setUp(): void
    {
        $this->phoneVerificationService = new PhoneVerificationService();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validPhoneNumberProvider')]
    public function testValidPhoneNumbers(string $phoneNumber, string $expectedCountryCode, string $expectedCountryName): void
    {
        $result = $this->phoneVerificationService->verifyPhone($phoneNumber);

        $this->assertTrue($result['success']);
        $this->assertStringStartsWith('+', $result['phone']);
        $this->assertEquals($expectedCountryCode, $result['country_code']);
        $this->assertEquals($expectedCountryName, $result['country_name']);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidPhoneNumberProvider')]
    public function testInvalidPhoneNumbers(string $phoneNumber): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->phoneVerificationService->verifyPhone($phoneNumber);
    }

    public static function validPhoneNumberProvider(): array
    {
        return [
            //MOLDOVA
            ['+373(533)47684', 'MD', 'Moldova'],
            ['+373(22)582190', 'MD', 'Moldova'],
            ['+373(778)55555', 'MD', 'Moldova'],
            ['+373 775 55555', 'MD', 'Moldova'],
            ['+373 (775) 44595', 'MD', 'Moldova'],
            //RUSSIA
            ['+79261234567', 'RU', 'Russia'],
            ['+7(4742)858539', 'RU', 'Russia'],
            ['+7(4812)312144', 'RU', 'Russia'],
            //UKRAINE
            ['+380501234567', 'UA', 'Ukraine'],
            //USA
            ['+12125551234', 'US', 'USA'],
            ['+18634303534', 'US', 'USA'],
            //GERMANY
            ['+4930123456', 'DE', 'Germany'],
            ['+49 06534675108', 'DE', 'Germany'],
            //ITALY
            ['+39 333 3333333', 'IT', 'Italy']
        ];
    }

    public static function invalidPhoneNumberProvider(): array
    {
        return [
            ['123'],
            ['abc'],
            ['+999123456789'],
            [''],
            ['+'],
            ['+312312312asxdfasdfsdfas']
        ];
    }
}
