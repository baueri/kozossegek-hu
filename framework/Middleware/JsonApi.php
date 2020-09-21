<?php


namespace Framework\Middleware;


use Framework\Http\Response;

class JsonApi implements Middleware
{

    public function handle()
    {
        Response::asJson();
    }
}