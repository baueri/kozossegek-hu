<?php

declare(strict_types=1);

namespace App\Admin\Components\DebugBar;

class MessageTab extends DebugBarTab
{
    private array $messages = [];

    public function getTitle(): string
    {
        return 'Messages';
    }

    public function getBadge(): ?int
    {
        return count($this->messages) ?: null;
    }

    public function icon(): string
    {
        return 'fa fa-info-circle';
    }

    public function render(): string
    {
        if (empty($this->messages)) {
            return '<p class="dbg-empty">No messages.</p>';
        }

        $items = '';
        foreach ($this->messages as $message) {
            $items .= '<li class="dbg-message-item">'
                . '<i class="fa fa-circle dbg-msg-dot"></i>'
                . '<span>' . $message . '</span>'
                . '</li>';
        }

        return '<ul class="dbg-message-list">' . $items . '</ul>';
    }

    public function putMessage(string $message): void
    {
        $this->messages[] = $message;
    }
}
