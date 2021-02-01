<?php

namespace App\Portal\Controllers;

class TestController
{
    public function test()
    {
        echo '<pre>';
        print session_id() . '<br>';

        session_destroy();

        session_id(session_create_id());

        session_start();


        print session_id();
    }
}
