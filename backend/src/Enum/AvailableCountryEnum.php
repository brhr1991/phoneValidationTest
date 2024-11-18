<?php

declare(strict_types=1);

namespace App\Enum;

enum AvailableCountryEnum: string
{
    //NEED TO ADD ALL AVAILABLE COUNTRIES
    case US = 'USA';
    case RU = 'Russia';
    case UA = 'Ukraine';
    case CN = 'China';
    case FR = 'France';
    case DE = 'Germany';
    case IT = 'Italy';
    case ES = 'Spain';
    case CA = 'Canada';
    case MD = 'Moldova';

    /**
     *
     * @param string $code
     * @return string|null
     */
    public static function getCountryName(string $code): ?string
    {
        return match ($code) {
            'US' => self::US->value,
            'RU' => self::RU->value,
            'UA' => self::UA->value,
            'CN' => self::CN->value,
            'FR' => self::FR->value,
            'DE' => self::DE->value,
            'IT' => self::IT->value,
            'ES' => self::ES->value,
            'CA' => self::CA->value,
            'MD' => self::MD->value,

            default => null,
        };
    }
}
