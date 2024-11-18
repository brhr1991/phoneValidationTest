<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\AvailableCountryEnum;
use InvalidArgumentException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

readonly class PhoneVerificationService
{
    private PhoneNumberUtil $phoneUtil;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * @param string $phone
     * @return array<string,mixed>
     */
    public function verifyPhone(string $phone): array
    {
        try {
            $phoneNumber = $this->phoneUtil->parse($phone);

            if (!$this->phoneUtil->isValidNumber($phoneNumber)) {
                throw new InvalidArgumentException('Phone number is invalid');
            }

            $regionCode = $this->phoneUtil->getRegionCodeForNumber($phoneNumber);
            $formattedNumber = $this->phoneUtil->format($phoneNumber, PhoneNumberFormat::E164);
            $countryName = AvailableCountryEnum::getCountryName($regionCode);

            return [
                'success' => true,
                'phone' => $formattedNumber,
                'country_name' => $countryName,
                'country_code' => $regionCode
            ];
        } catch (NumberParseException $e) {
            throw new InvalidArgumentException('Can not parse phone number: ' . $e->getMessage());
        }
    }
}
