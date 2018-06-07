<?php

namespace App\Http\Controllers;

use App\Books;
use Illuminate\Http\Request;

class HomeController extends BaseController
{

    public function search(Request $request)
    {
        $q = $request->get('q');
        $paginator = Books::paginate();
        if ($q){
            $paginator = Books::search($q)->paginate();
        }
        return $this->view('home', compact('paginator', 'q'));
    }
}
