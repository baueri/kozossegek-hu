<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ChurchGroupView;
use Framework\Support\Collection;

final class SearchEngineKeywordCollector
{
    private Collection $keywords;

    final public function __construct(private readonly ChurchGroupView $churchGroup)
    {
        $this->keywords = $this->churchGroup->tags->map->translate();
    }

    public function getKeywords(): Collection
    {
        $this->collect();
        return $this->keywords
            ->filter(fn ($word) => mb_strlen($word) >= 3)
            ->unique();
    }

    private function collect(): void
    {
        $churchGroup = $this->churchGroup;

        $this->pushWords($churchGroup->name);
        $this->pushWords($churchGroup->denomination());
        $this->pushWords($churchGroup->getAgeGroups()->map->translate());
        $this->pushWords($churchGroup->getDays()->map->translate());
        $this->pushWords($churchGroup->occasionFrequency());
        $this->pushWords($churchGroup->city);
        $this->pushWords(str_replace('atya', '', (string) $churchGroup->leader_name));
        $this->pushWords($churchGroup->group_leaders);
        $this->pushWords($churchGroup->institute_name);
        $this->pushWords($churchGroup->institute_name2);
        $this->pushWords($churchGroup->spiritual_movement);
        $this->pushWords($churchGroup->district);
    }

    private function pushWords(null|string|Collection $text): void
    {
        if (!$text) {
            return;
        }

        if ($text instanceof Collection) {
            $text->each(fn ($word) => $this->pushWords((string) $word));
            return;
        }

        $this->keywords->push(...$this->textToArray($text));
    }

    private function textToArray(string $text): array
    {
        preg_match_all('/[\p{L}-]+/u', $text, $matches);
        return $matches[0] ?? [];
    }
}
