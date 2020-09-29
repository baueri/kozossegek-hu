<?php


namespace App\Portal\Controllers;


use App\Portal\Test\TestPipe1;
use App\Portal\Test\TestPipe2;
use Framework\Support\PipeLine\PipeLine;

class TestController
{
    public function testPipe(PipeLine $pipeLine)
    {
        $builder = builder()->select('*')->from('groups');
        $pipeLine->send($builder)
            ->pipes([TestPipe1::class, TestPipe2::class])
            ->run();

        dd($builder->toSql());

    }
}