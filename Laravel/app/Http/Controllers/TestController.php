<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @return string
     */
    public function test(): stream_set_blocking{
        return "test";
    }
}
