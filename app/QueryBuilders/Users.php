<?php

namespace App\QueryBuilders;

use App\Models\User;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\SoftDeletes;
use Framework\Support\StringHelper;

/**
 * @phpstan-extends EntityQueryBuilder<\App\Models\User>
 */
class Users extends EntityQueryBuilder
{
    use HasManyChurchGroupViews;
    use SoftDeletes;

    public static function getModelClass(): string
    {
        return User::class;
    }

    public function byAuth(?string $username): static
    {
        $this->notDeleted();

        if (StringHelper::isEmail($username)) {
            $this->where('email', $username);
        } else {
            $this->where('username', $username);
        }

        return $this;
    }

    public function byEmail(?string $email): static
    {
        return $this->where('email', $email)
            ->notDeleted();
    }

    public function search(?string $keyword): static
    {
        $this->notDeleted()
            ->limit(20);

        if (filter_var($keyword, FILTER_VALIDATE_EMAIL)) {
            $this->where('email', 'like', "%$keyword%");
        } else {
            $this->where('name', 'like', "%$keyword%");
        }

        return $this;
    }
}
