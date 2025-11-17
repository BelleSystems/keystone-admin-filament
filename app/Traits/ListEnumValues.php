<?php

namespace App\Traits;

/**
 * @method static cases()
 */
trait ListEnumValues
{
    /**
     * Get all enum string values.
     *
     * @return array
     */
    public static function listValues(): array
    {
        return array_map(
            fn($case) => $case->value,
            static::cases()
        );
    }
}
