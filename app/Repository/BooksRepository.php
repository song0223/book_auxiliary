<?php

namespace App\Repository;

use App\Books;
use App\BookChapter;
use App\Libraries\BxwxBooksSpider;
use App\Libraries\BxwxBooksZjSpider;
use App\Libraries\BxwxZjSpider;
use Goutte\Client;

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


    public function import($url)
    {
        $client = new Client();
        $arr = parse_url($url);
        $bxwx_id = trim($arr['path'], '/');
        if (!Books::where('bxwx_id', $bxwx_id)->exists()) {
            $bxwx_books_spider = new BxwxBooksSpider($client, $url);
            $book_id = $this->saveBook($bxwx_books_spider, $bxwx_id);
            if ($book_id) {
                $bxwx_books_zj_spider = new BxwxBooksZjSpider($client, $url);
                $book_zj_urls = $bxwx_books_zj_spider->getZjUrl();
                foreach ($book_zj_urls as $book_zj_url) {
                    $arr = parse_url($book_zj_url);
                    $zj_id = explode('/', $arr['path']);
                    $zj_id = explode('.', $zj_id[2]);
                    if (BookChapter::where('bxwx_id', $zj_id[0])->exists()) {
                        continue;
                    }
                    $bxwx_zj_spider = new BxwxZjSpider($client, $book_zj_url);
                    $this->saveZj($bxwx_zj_spider, $book_id, $zj_id[0]);
                }
            }
        }
    }

    protected function saveBook(BxwxBooksSpider $bxwx_books_spider, $bxwx_id)
    {
        $book_model = new Books;
        $book_model->bxwx_id = $bxwx_id;
        $book_model->bxwx_url = $bxwx_books_spider->getUrl();
        $book_model->author = $bxwx_books_spider->getAuthor();
        $book_model->title = $bxwx_books_spider->getTitle();
        $book_model->type = $bxwx_books_spider->getType();
        $book_model->image = $bxwx_books_spider->getImage();
        $book_model->introduction = $bxwx_books_spider->getIntroduction();
        $book_model->is_ending = $bxwx_books_spider->getIsEnding();
        $book_model->read_count = 0;
        $book_model->save();
        return $book_model->id;
    }

    protected function saveZj(BxwxZjSpider $bxwx_zj_spider, $book_id, $bxwx_id)
    {
        $book_chapter_model = new BookChapter;
        $book_chapter_model->book_id = $book_id;
        $book_chapter_model->bxwx_id = $bxwx_id;
        $book_chapter_model->bxwx_url = $bxwx_zj_spider->getUrl();
        $book_chapter_model->title = $bxwx_zj_spider->getZjTitle();
        $book_chapter_model->content = $bxwx_zj_spider->getZjText();
        $book_chapter_model->sort = $bxwx_id;
        $book_chapter_model->save();
    }
}