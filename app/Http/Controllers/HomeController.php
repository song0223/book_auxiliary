<?php

namespace App\Http\Controllers;

use App\BookChapter;
use App\Books;
use Illuminate\Http\Request;

class HomeController extends BaseController
{

    public function search(Request $request,$t = 0, $type = 1, $query = null)
    {
        $paginator = Books::paginate();
        if ($t){
            $paginator = Books::where('type', $t)->paginate();
        }
        if ($type == 1) {
            if ($query) {
                $paginator = Books::search($query)->paginate();
            }
        } else {
            if ($query) {
                $paginator = BookChapter::search($query)->paginate();
            }
        }
        return $this->view('home', compact('paginator', 'query', 'type', 't'));
    }
}
