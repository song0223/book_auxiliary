<?php

namespace App\Http\Controllers;

use App\BookChapter;
use App\Books;
use Illuminate\Http\Request;

class HomeController extends BaseController
{

    public function search(Request $request)
    {
        $q = $request->get('query');
        $paginator = Books::search($q)->paginate();
        dd($paginator);
        if ($q){
            //$paginator = BookChapter::search($q)->paginate();
        }
        return $this->view('home', compact('paginator', 'q'));
    }
}
