<?php

declare(strict_types=1);

namespace App\EventListeners;

use App\Admin\Components\DebugBar\DebugBar;
use App\Admin\Components\DebugBar\MessageTab;
use Exception;
use Framework\Event\EventListener;
use Framework\Translation\TranslationMissing;

/**
 * Put missing translation keys into Debugbar's Messages tab
 */
class MissingTranslationListener implements EventListener
{
    protected static array $loggedMissingTranslations = [];

    public function __construct(
        protected DebugBar $debugBar
    ) {
    }

    /**
     * @param TranslationMissing $event
     * @throws Exception
     */
    public function trigger($event): void
    {
        if (!in_array($event->key, static::$loggedMissingTranslations[$event->lang] ?? [])) {
            if (!isset(static::$loggedMissingTranslations[$event->lang])) {
                static::$loggedMissingTranslations[$event->lang] = [];
            }
            static::$loggedMissingTranslations[$event->lang][] = $event->key;
            $this->debugBar->getTab(MessageTab::class)->putMessage("missing translation ({$event->lang}): {$event->key}");
        }
    }
}