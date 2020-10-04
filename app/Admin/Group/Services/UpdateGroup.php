<?php

namespace App\Admin\Group\Services;

/**
 * Description of UpdateGroup
 *
 * @author ivan
 */
class UpdateGroup
{

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

    /**
     *
     * @param int $id
     * @param array $data
     */
    public function update($id, array $data)
    {
        $group = $this->repository->findOrFail($id);

        $data['age_group'] = implode(',', $data['age_group']);
        $data['tags'] = implode(',', $data['tags']);

        $group->update($data);

        $this->repository->update($group);

        \Framework\Http\Message::success('Sikeres mentés.');
    }
}
