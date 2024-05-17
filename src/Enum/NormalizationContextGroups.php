<?php

namespace App\Enum;

enum NormalizationContextGroups: string
{
    case DEFAULT = "default:read";
    case CATEGORY = "category:read";
    case FORM = "form:read";
    case CAPTURED_DATA = "captured_data:read";
    case FIELD = "field:read";
    case IMAGE = "image:read";
    case USER = "user:read";
}
