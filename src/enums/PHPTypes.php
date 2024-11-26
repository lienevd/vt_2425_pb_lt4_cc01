<?php

namespace Src\Enums;

enum PHPTypes: string
{
    case INT = "integer";
    case STR = "string";
    case FLOAT = "float";
    case BOOL = "boolean";
    case ARRAY = "array";
    case OBJ = "object";
    case NULL = "null";
    case MIXED = "mixed";

    // Convert a PHP type string to the corresponding enum case
    public static function fromPhpType(string $type): PHPTypes
    {
        foreach (self::cases() as $phpType) {
            if ($phpType->value === $type) {
                return $phpType;
            }
        }

        throw new \InvalidArgumentException("Invalid PHP type string: $type");
    }
}
