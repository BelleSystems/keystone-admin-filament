<?php

namespace App\Enums;

use App\Traits\ListEnumValues;

/**
 * The status of the booking.
 *
 * @var int
 */
enum BookingStatus: int
{
    /**
     * Add trait for listing values of an ENUM
     */
    use ListEnumValues;

    /**
     * The prospect status.
     *
     * @var int
     */
    case PROSPECT = 1;

    /**
     * The tentative status.
     *
     * @var int
     */
    case TENTATIVE = 2;
    
    /**
     * The definite status.
     *
     * @var int
     */
    case DEFINITE = 3;

    /**
     * The cancelled status.
     *
     * @var int
     */
    case CANCELLED = 4;
    

    /**
     * The turned down status.
     *
     * @var int
     */
    case TURNED_DOWN = 5;
    
    /**
     * Get the integer value from a string status name.
     *
     * @param string $statusName
     * @return int
     * @throws \InvalidArgumentException
     */
    public static function fromName(string $statusName): int
    {
        return match($statusName) {
            'PROSPECT' => self::PROSPECT->value,
            'TENTATIVE' => self::TENTATIVE->value,
            'DEFINITE' => self::DEFINITE->value,
            'CANCELLED' => self::CANCELLED->value,
            'TURNED_DOWN' => self::TURNED_DOWN->value,
            default => throw new \InvalidArgumentException("Invalid booking status: {$statusName}")
        };
    }
    
}
