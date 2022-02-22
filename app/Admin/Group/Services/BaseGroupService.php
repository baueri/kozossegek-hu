<?php

namespace App\Admin\Group\Services;

use App\Helpers\FileHelper;
use App\Helpers\GroupHelper;
use App\QueryBuilders\GroupViews;
use App\Repositories\Groups;
use App\Repositories\Institutes;
use App\Services\RebuildSearchEngine;
use App\Storage\Base64Image;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\File\Enums\FileType;
use Framework\File\File;
use Framework\File\FileManager;
use Framework\Traits\ManagesErrors;
use Legacy\Group;

abstract class BaseGroupService
{
    use ManagesErrors;

    public function __construct(
        protected Groups $repository,
        private RebuildSearchEngine $searchEngineRebuilder,
        private FileManager $fileManager,
        protected Institutes $institutes
    ) {
    }

    protected function updateSearchEngine(Group $group)
    {
        $this->searchEngineRebuilder->updateSearchEngine(GroupViews::query()->find($group->getId()));
    }

    protected function syncTags(Group $group, array $tags = [])
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
     * @throws \Exception
     */
    protected function syncImages(Group $group, $images)
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
    protected function uploadDocument(Group $group, ?array $document = null): ?File
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
            'age_group',
            'occasion_frequency',
            'description',
            'group_leaders',
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || !$data[$field]) {
                $this->getErrors()['error.required'][] = $field;
            }
        }

        return !$this->hasErrors();
    }
}
