<?php

namespace App\Admin\Group\Services;

use App\Enums\AgeGroup;
use App\Enums\OccasionFrequency;
use App\Helpers\FileHelper;
use App\Helpers\GroupHelper;
use App\Models\ChurchGroup;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\ChurchGroupViews;
use App\QueryBuilders\Institutes;
use App\Services\RebuildSearchEngine;
use App\Storage\Base64Image;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\File\Enums\FileType;
use Framework\File\File;
use Framework\File\FileManager;
use Framework\Traits\ManagesErrors;

abstract class BaseGroupService
{
    public const ALLOWED_TAGS = '<a><b><u><ul><ol><li><p><pre><h1><h2><h3><h4><h5><h6><blockquote>';

    use ManagesErrors;

    public function __construct(
        protected ChurchGroups $repository,
        private readonly RebuildSearchEngine $searchEngineBuilder,
        private readonly FileManager         $fileManager,
        protected Institutes $institutes
    ) {
    }

    protected function updateSearchEngine(ChurchGroup $group): void
    {
        $this->searchEngineBuilder->updateSearchEngine(ChurchGroupViews::query()->find($group->getId()));
    }

    protected function syncTags(ChurchGroup $group, array $tags = []): void
    {
        $delete = builder('group_tags')->where('group_id', $group->id);

        if ($tags) {
            $delete->whereNotIn('tag', $tags);
        }

        $delete->delete();

        foreach ($tags as $tag) {
            db()->execute('replace into group_tags (group_id, tag) values (?, ?)', $group->id, $tag);
        }
    }

    /**
     * @throws Exception
     */
    protected function syncImages(ChurchGroup $group, $images)
    {
        $images = array_filter($images);

        if (!$images) {
            return null;
        }

        foreach ($images as $imageSource) {
            $image = new Base64Image($imageSource);
            $image->saveImage($group->getStorageImageDir() . $group->id . '_1.jpg');
        }
    }

    /**
     * @throws FileTypeNotAllowedException
     */
    protected function uploadDocument(ChurchGroup $group, ?array $document = null): ?File
    {
        if (!$document || (isset($document['error']) && $document['error'] > 0)) {
            return null;
        }

        $file = File::createFromFormData($document);

        if (!$file->mainTypeIs([FileType::DOCUMENT, FileType::PDF, FileType::IMAGE])) {
            throw new FileTypeNotAllowedException();
        }

        if ($group->hasDocument()) {
            $group->getDocument()->delete();
        }

        $extension = FileHelper::getExtension($document['name']);

        $file->move(GroupHelper::getStoragePath($group->id), "igazolas.{$extension}");

        $file->setFileName($file->getFilePath());

        return $file;
    }

    protected function validate(array $data): bool
    {
        $requiredFields = [
            'name',
            'description',
            'group_leaders',
        ];

        foreach (explode(',', $data['age_group']) as $ageGroup) {
            if (!AgeGroup::tryFrom($ageGroup)) {
                $this->pushError('age_group.invalid');
            }
        }

        if (!OccasionFrequency::tryFrom($data['occasion_frequency'])) {
            $this->pushError('occasion_frequency.invalid');
        }

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || !$data[$field]) {
                $this->getErrors()->push("{$field}.required");
            }
        }

        return !$this->hasErrors();
    }
}
