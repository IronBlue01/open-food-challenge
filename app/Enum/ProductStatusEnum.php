<?php

namespace App\Enum;

enum ProductStatusEnum: int 
{
    case PUBLISH = 1;
    case DRAFT   = 2;
    case TRASH   = 3;

    public function id(): int
    {
        return match ($this) { 
            self::PUBLISH => 1,
            self::DRAFT   => 2,
            self::TRASH   => 3,

        };
    }

    public function descricao(): string
    {
        return match ($this) {
            self::PUBLISH => 'published',
            self::DRAFT   => 'draft',
            self::TRASH   => 'trash',
        };
    }
}
