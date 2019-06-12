<?php

namespace App\Http\Controllers;

use App\BookChapter;
use App\Books;
use App\Jobs\BookSpider;
use App\Jobs\SendVerifyCode;
use App\Libraries\MyRedis;
use App\Repository\BooksRepository;
use Illuminate\Http\Request;

class HomeController extends BaseController
{

    public function search(Request $request, $t = 0, $type = 1, $book_id = 0, $query = null)
    {
        $paginator = Books::paginate();
        if ($t) {
            $paginator = Books::where('type', $t)->paginate();
        }
        if ($type == 1) {
            if ($query) {
                $paginator = Books::search($query)->paginate();
            }
        } elseif ($type == 2) {
            if ($query) {
                $paginator = BookChapter::search($query)->paginate();
            }
        } else {
            if ($query && $book_id) {
                $paginator = BookChapter::search($query)->where('book_id', $book_id)->paginate();
            }
        }

        return $this->view('home', compact('paginator', 'query', 'type', 't'));
    }

    public function book(Request $request, $id, $query = null)
    {
        $data = [];
        if (!empty($id)) {
            //if (!MyRedis::exists('books:search:info:' . $id)) {
                $data['book'] = Books::find($id);
                $data['book']->increment('read_count', 1);
                $book_chapter_model = new BookChapter;
                $data['book_chapter'] = $book_chapter_model->getChapterByBookId($id);
                /*MyRedis::set('books:search:info:' . $id, $data);
            } else {
                $data = MyRedis::get('books:search:info:' . $id);
            }*/
        }
        $data['query'] = $query;
        return $this->view('book', $data);
    }

    public function desc(Request $request, $id)
    {
        $book_chapter = [];
        if (!empty($id)) {
            //if (!MyRedis::exists('books:search:desc:' . $id)) {
                $book_chapter = BookChapter::find($id);
                Books::find($book_chapter->book->id)->increment('read_count', 1);
                MyRedis::set('books:search:desc:' . $id, $book_chapter);
            /*} else {
                $book_chapter = MyRedis::get('books:search:desc:' . $id);
            }*/
        }
        return $this->view('chapter', compact('book_chapter'));
    }

    public function import(Request $request)
    {

        return $this->view('import');
    }

    public function importBook(Request $request, $query = null)
    {
        $data = [];
        if ($url = $request->get('url')) {
            $books_repository = new BooksRepository();
            $result = $books_repository->import($url);
            $data['code'] = 200;
            if ($result) {
                $data['msg'] = 'success';
            } else {
                $data['msg'] = '书籍已经存在！';
            }
        }
        return response()->json($data, 200);
    }
}
