<?php

namespace App\Http\Controllers;

use App\Books;

class BaseController extends Controller
{

    public $search_menu;

    public function __construct()
    {
        $menu = [
            1 => '搜索小说',
            2 => '全站搜索',
            3 => '书内搜索',
        ];
        $this->search_menu = $menu;
    }

    protected function view($view, $data = array())
    {
        $data = array_merge(
            [
                'basic_data' => [
                    'user'        => request()->user(),
                    'menus'       => $this->type(),
                    'search_menu' => $this->search_menu,
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