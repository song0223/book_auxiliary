<?php

namespace App\Http\Controllers;

use App\BookChapter;
use App\Books;
use App\Jobs\SendVerifyCode;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Type;

class ApiController extends Controller
{
    public function sendVerifyCode(Request $request)
    {
        $this->validate($request, ['phone' => 'required|size:11|exists:users']);

        dispatch(new SendVerifyCode($request->phone));

        return ['success' => true];
    }

    public function index(Request $request)
    {
        $data = [];
        //热门
        $model = new Books;
        $data['hot'] = $model->orderBy('read_count', 'desc')->limit('5')->get();
	$data['type'] = Books::typeMap();
        $data['list'][Books::XH] = $model->where('type', Books::XH)->orderBy('read_count', 'desc')->limit('5')->get();
        $data['list'][Books::WX] = $model->where('type', Books::WX)->orderBy('read_count', 'desc')->limit('5')->get();
        $data['list'][Books::YQ] = $model->where('type', Books::YQ)->orderBy('read_count', 'desc')->limit('5')->get();
        $data['list'][Books::LS] = $model->where('type', Books::LS)->orderBy('read_count', 'desc')->limit('5')->get();
        $data['list'][Books::YX] = $model->where('type', Books::YX)->orderBy('read_count', 'desc')->limit('5')->get();
        $data['list'][Books::Ly] = $model->where('type', Books::Ly)->orderBy('read_count', 'desc')->limit('5')->get();
        return response()->json($data);
    }

    public function list(Request $request, $type = null)
    {
        $data = [];
        $model = new Books;
        if ($type){
            $data['list'] = $model->where('type', $type)->orderBy('read_count', 'desc')->paginate();
        }else{
            $data['list'] = $model->orderBy('read_count', 'desc')->paginate();

        }
        return response()->json($data);
    }

    public function desc(Request $request, $id)
    {
        $data = [];
        if ($id){
            $data['book'] = Books::find($id);
            $data['book_chapter'] = (new BookChapter)->getChapterByBookId($id);
        }
        return response()->json($data);
    }

    public function chapter(Request $request, $id)
    {
        $data = [];
        if ($id){
            $model = new BookChapter;
            $data['data'] = $model->find($id);
            $data['next'] = $model->getNextArticleId($id, $data['data']->book->id) ?? 0;
            $data['prev'] = $model->getPrevArticleId($id, $data['data']->book->id) ?? 0;
        }
        return response()->json($data);
    }
}
