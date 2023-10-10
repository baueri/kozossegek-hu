<?php


namespace Framework\Http\View\Bootstrappers;

use Framework\Bootstrapper;
use Framework\Http\View\Directives\ComponentDirective;
use Framework\Http\View\Directives\ComponentExpression;
use Framework\Http\View\Directives\EchoDirective;
use Framework\Http\View\Directives\ExtendsDirective;
use Framework\Http\View\Directives\IfDirective;
use Framework\Http\View\Directives\IncludeDirective;
use Framework\Http\View\Directives\LangDirective;
use Framework\Http\View\Directives\RouteDirective;
use Framework\Http\View\Directives\SectionDirective;
use Framework\Http\View\Directives\YieldDirective;
use Framework\Http\View\ViewParser;
use Framework\Http\View\Directives\ForeachDirective;

class BootDirectives implements Bootstrapper
{

    public function boot()
    {
        ViewParser::registerDirective(new ExtendsDirective());
        ViewParser::registerDirective(new RouteDirective());
        ViewParser::registerDirective(new IncludeDirective());
        ViewParser::registerDirective(new EchoDirective());
        ViewParser::registerDirective(new LangDirective());
        ViewParser::registerDirective(new SectionDirective());
        ViewParser::registerDirective(new YieldDirective());
        ViewParser::registerDirective(new IfDirective());
        ViewParser::registerDirective(new ForeachDirective());
        ViewParser::registerDirective(new ComponentDirective());
        ViewParser::registerDirective(new ComponentExpression());
    }
}
