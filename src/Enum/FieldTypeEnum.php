<?php

namespace App\Enum;

enum FieldTypeEnum: string
{
    case TEXT = 'text';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case URL = 'url';
    case NUMBER = 'number';
    case DATE = 'date';
    case SECRET_CODE = 'password';
    case SELECT = 'select';
    case TEXTAREA = 'textarea';
}
