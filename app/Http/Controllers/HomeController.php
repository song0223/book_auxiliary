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
        $paginator = Books::paginate();
        if ($q){
            $paginator = BookChapter::search($q)->paginate();
        }
        dd($paginator);
        return $this->view('home', compact('paginator', 'q'));
    }
}
