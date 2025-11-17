<?php

namespace App\Enums;

use App\Traits\ListEnumValues;

enum RequirementFieldType: string
{
    /**
     * Add trait for listing values of an ENUM
     */
    use ListEnumValues;

    /**
     * The text field type.
     *
     * @var string
     */
    case TEXT = 'TEXT';

    /**
     * The number field type.
     *
     * @var string
     */
    case NUMBER = 'NUMBER';

    /**
     * The date field type.
     *
     * @var string
     */
    case DATE = 'DATE';

    /**
     * The date field type.
     *
     * @var string
     */
    case BOOLEAN = 'BOOLEAN';
    
}
