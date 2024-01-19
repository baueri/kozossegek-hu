<?php

declare(strict_types=1);

namespace App\Admin\Components\DebugBar;

class MessageTab extends DebugBarTab
{
    private ?float $totalLoadTime = null;
    
    private array $messages = [];

    public function getTitle(): string
    {
        return 'Messages';
    }

    public function icon(): string
    {
        return 'fa fa-info';
    }

    public function render(): string
    {
        $output = '<code>';
        foreach ($this->messages as $message) {
            $output .= "<div>{$message}</div>";
        }

        return $output . '</code>';
    }

    public function putMessage(string $message): void
    {
        $this->messages[] = $message;
    }
}
