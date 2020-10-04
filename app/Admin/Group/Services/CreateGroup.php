<?php

namespace App\Admin\Group\Services;

/**
 * Description of CreateGrooup
 *
 * @author ivan
 */
class CreateGroup {

    /**
     * @var \App\Repositories\GroupRepository
     */
    private $repository;

    /**
     *
     * @param \App\Repositories\GroupRepository $repository
     */
    public function __construct(\App\Repositories\GroupRepository $repository) {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        
        $data['age_group'] = implode(',', $data['age_group']);
        $data['tags'] = implode(',', $data['tags']);

        $group = $this->repository->create($data);

        \Framework\Http\Message::success('Közösség létrehozva.');

        return $group;
    }
}
