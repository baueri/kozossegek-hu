<?php

declare(strict_types=1);

namespace App\Admin\Components\DebugBar;

class RequestTab extends DebugBarTab
{
    public function getTitle(): string
    {
        return 'Request';
    }

    public function icon(): string
    {
        return 'fa fa-exchange-alt';
    }

    public function render(): string
    {
        $request = request();
        $method  = htmlspecialchars($request->requestMethod->name);
        $uri     = htmlspecialchars($request->uri);
        $query   = htmlspecialchars(json_encode($request->request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $headers = htmlspecialchars(json_encode($request->headers->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $session = htmlspecialchars(json_encode($_SESSION ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $methodClass = match ($method) {
            'GET'    => 'dbg-badge-success',
            'POST'   => 'dbg-badge-accent',
            'PUT', 'PATCH' => 'dbg-badge-warning',
            'DELETE' => 'dbg-badge-danger',
            default  => 'dbg-badge-muted',
        };

        return <<<HTML
            <dl class="dbg-kv">
                <div><dt>Method</dt><dd><span class="dbg-badge {$methodClass}">{$method}</span></dd></div>
                <div><dt>URI</dt><dd>{$uri}</dd></div>
            </dl>
            <details class="dbg-details">
                <summary>Query / Body params</summary>
                <pre class="dbg-pre">{$query}</pre>
            </details>
            <details class="dbg-details">
                <summary>Headers</summary>
                <pre class="dbg-pre">{$headers}</pre>
            </details>
            <details class="dbg-details" open>
                <summary>Session</summary>
                <pre class="dbg-pre">{$session}</pre>
            </details>
        HTML;
    }
}
