<?php

declare(strict_types=1);

$data = $__mint_data ?? [];
$iconName = (string) ($data['name'] ?? '');
$iconTitle = (string) ($data['title'] ?? '');
$additionalClass = (string) (($data['additionalClass'] ?? null) ?? ($data['additional-class'] ?? null) ?? '');

echo (new \App\Http\Components\FontawesomeIcon)->render($iconName, $iconTitle, $additionalClass);
