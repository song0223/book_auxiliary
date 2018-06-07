<?php

namespace App\Http\Controllers;

use App\Books;

class BaseController extends Controller
{
    protected function view($view, $data = array())
    {
        $data = array_merge(
            [
                'basic_data' => [
                    'user' => request()->user(),
                    'menus' => $this->type(),
                ],
            ],
            $data
        );
        return view($view, $data);
    }


    protected function type()
    {
        return Books::typeMap();
    }
}