<?php

namespace App\Enums;

enum PostContentEnum: string
{
    case HEADING = 'heading';
    case PARAGRAPH = 'paragraph';
    case LIST = 'list';
    case IMAGE = 'image';
}
