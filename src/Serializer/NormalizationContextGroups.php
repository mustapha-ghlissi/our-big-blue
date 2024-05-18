<?php

namespace App\Serializer;

final class NormalizationContextGroups
{
    public const DEFAULT = "default:read";
    public const CATEGORY = "category:read";
    public const FORM = "form:read";
    public const CAPTURED_DATA = "captured_data:read";
    public const FIELD = "field:read";
    public const IMAGE = "image:read";
    public const USER = "user:read";
}
