<?php

declare(strict_types=1);

namespace App\Admin\Components\DebugBar;

class RequestTab extends DebugBarTab
{
    public function getTitle(): string
    {
        return 'request';
    }

    public function render(): string
    {
        return '<code>' . $this->getRequestInfo() . '</code>';
    }

    public function icon(): string
    {
        return 'fa fa-exchange-alt';
    }

    private function getRequestInfo(): string
    {
        $request = request();
        $output = '<div><b>Request Method:</b> ' . $request->requestMethod->name . '</div>';
        $output .= '<div><b>Request URI:</b> ' . $request->uri . '</div>';
        $output .= '<div><b>Request Query:</b> ' . $request->request->toJson() . '</div>';
        $output .= '<div><b>Request Headers:</b> ' . $request->headers->toJson() . '</div>';
        $output .= '<div><b>Session:</b> ' . json_encode($_SESSION) . '</div>';
        return $output;
    }
}