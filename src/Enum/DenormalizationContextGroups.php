<?php

namespace App\Enum;

enum DenormalizationContextGroups: string
{
    case DEFAULT = "default:write";
    case CATEGORY = "category:write";
    case FORM = "form:write";
    case CAPTURED_DATA = "captured_data:write";
    case FIELD = "field:write";
    case IMAGE = "image:write";
    case USER = "user:write";
}
