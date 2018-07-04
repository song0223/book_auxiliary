<?php

namespace App\Jobs;

use App\BookChapter;
use App\Books;
use App\Libraries\BxwxBooksSpider;
use App\Libraries\BxwxBooksZjSpider;
use App\Libraries\BxwxZjSpider;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Goutte\Client;

class BookSpider implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $arr = parse_url($this->url);
        $bxwx_id = trim($arr['path'], '/');
        if (!Books::where('bxwx_id', $bxwx_id)->exists()) {
            $bxwx_books_spider = new BxwxBooksSpider($client, $this->url);
            $book_id = $this->saveBook($bxwx_books_spider);
            if ($book_id) {
                $bxwx_books_zj_spider = new BxwxBooksZjSpider($client, $this->url);
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

    protected function saveBook(BxwxBooksSpider $bxwx_books_spider)
    {
        $book_model = new Books;
        $book_model->bxwx_id = $bxwx_books_spider->getUrl();
        $book_model->bxwx_url = $this->url;
        $book_model->author = $bxwx_books_spider->getAuthor();
        $book_model->title = $bxwx_books_spider->getTitle();
        $book_model->type = $bxwx_books_spider->getType();
        $book_model->image = $bxwx_books_spider->getImage();
        $book_model->introduction = $bxwx_books_spider->getIntroduction();
        $book_model->is_ending = $bxwx_books_spider->getIsEnding();
        $book_model->save();
        return $book_model->id;
    }

    protected function saveZj(BxwxZjSpider $bxwx_zj_spider, $book_id, $bxwx_id)
    {
        $book_chapter_model = new BookChapter;
        $book_chapter_model->book_id = $book_id;
        $book_chapter_model->bxwx_id = $bxwx_id;
        $book_chapter_model->bxwx_url = $bxwx_zj_spider->getUrl();
        $book_chapter_model->title   = $bxwx_zj_spider->getZjTitle();
        $book_chapter_model->content = $bxwx_zj_spider->getZjText();
        $book_chapter_model->sort    = $bxwx_id;
        $book_chapter_model->save();
    }
}
