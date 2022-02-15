<?php

/**
 * @template T
 * @param $item T class-string<T>
 * @return T
 */
function getItem($item)
{
    return $item;
}

echo getItem(\App\Models\UserLegacy::class)->name;

/**
 * @template T
 * @psalm-param $i T class-string<T>
 * @psalm-return T
 */
function a($i)
{
    return $i;
}

$a = a(\App\Models\UserLegacy::class)->firstName();
