<?php

declare(strict_types=1);

namespace App\Http\Components;

class FacebookShareButton
{
    public function render(string $url): string
    {
        return <<<EOT
            <div class="fb-share-button" data-href="{$url}" data-layout="button" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Megosztás</a></div>
        EOT;
    }
}
