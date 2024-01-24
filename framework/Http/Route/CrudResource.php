<?php

declare(strict_types=1);

namespace Framework\Http\Route;

use App\Enums\EnumTrait;
use Framework\Http\RequestMethod;
use Framework\Support\StringHelper;

enum CrudResource
{
    use EnumTrait;

    case index;
    case show;
    case edit;
    case update;
    case create;
    case store;
    case destroy;

    public function requestMethod(): RequestMethod
    {
        return match ($this) {
            self::index, self::edit, self::create, self::show => RequestMethod::GET,
            self::update, self::store => RequestMethod::POST,
            self::destroy => RequestMethod::DELETE
        };
    }

    public function uri(string $name): string
    {
        $singular = StringHelper::singular($name);
        return match ($this) {
            self::index, self::store => '',
            self::show, self::update, self::destroy => "{{$singular}}",
            self::edit => "{{$singular}}/edit",
            self::create => "create"
        };
    }

    public static function getResources(string $scope): array
    {
        return match ($scope) {
            '*', 'all' => self::cases(),
            'api' => [self::index, self::show, self::update, self::store, self::destroy]
        };
    }
}
