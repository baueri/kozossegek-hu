<?php

namespace App\Services;

use App\Enums\AgeGroup;
use App\Enums\WeekDay;
use App\Models\ChurchGroupView;
use Framework\Support\Collection;

final class SearchEngineKeywordCollector
{
    private Collection $keywords;

    final public function __construct(private readonly ChurchGroupView $churchGroup)
    {
        $this->keywords = collect(builder('v_group_tags')
            ->select('tag_name')->where('group_id', $this->churchGroup->getId())
            ->get())
            ->pluck('tag_name');
    }

    public function getKeywords(): Collection
    {
        $this->collect();
        return $this->keywords
            ->filter(fn ($word) => mb_strlen($word) >= 3)
            ->unique();
    }

    private function collect()
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
            $text->each(fn ($word) => $this->pushWords((string) $text));
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
