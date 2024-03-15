<?php
$resources = root()->resources();

return [
    $resources->path('routes/admin.xml'),
    $resources->path('routes/web.xml'),
    $resources->path('routes/api.xml'),
    $resources->path('routes/admin_api.xml')
];
