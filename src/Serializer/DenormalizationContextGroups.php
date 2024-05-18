<?php

namespace App\Serializer;

final class DenormalizationContextGroups
{
    public const DEFAULT = "default:write";
    public const CATEGORY = "category:write";
    public const FORM = "form:write";
    public const CAPTURED_DATA = "captured_data:write";
    public const FIELD = "field:write";
    public const IMAGE = "image:write";
    public const USER = "user:write";
}
