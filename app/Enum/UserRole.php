<?php

namespace App\Enum;

enum UserRole:string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case USER = 'user';
}
