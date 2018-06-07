<?php

namespace App\Repository;

use App\Books;

class BooksRepository
{
    public static function index()
    {
        $per_page = request('per_page', 15); ///*获取条数*/

        $type = request('type', '');

        $model = new Books();

        if ($type) {
            $model = $model->where('type', $type);
        }

        $paginate = $model->paginate($per_page);

        $data['items'] = $paginate;
        $data['pager']['total'] = $paginate->total();
        $data['pager']['last_page'] = $paginate->lastPage();
        $data['pager']['current_page'] = $paginate->currentPage();
        return $data;
    }

}