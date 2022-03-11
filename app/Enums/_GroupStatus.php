<?php

namespace App\Enums;

enum _GroupStatus: int
{
    case PENDING = 1;
    case REJECTED = -1;
    case APPROVED = 0;

    public function icon(): string
    {
        return match ($this->value) {
            self::PENDING->value => $this->getIcon('fa fa-sync text-warning', 'jóváhagyásra vár'),
            self::REJECTED->value => $this->getIcon('fa fa-ban text-danger', 'jóváhagyás visszautasítva'),
            self::APPROVED->value => $this->getIcon('fa fa-check-circle text-success', 'jóváhagyva')
        };
    }

    private function getIcon(string $class, string $title = ''): string
    {
        return "<i class='{$class}' title='$title'></i>";
    }
}
