<?php

namespace App\Jobs;

use App\Books;
use App\Libraries\BxwxBooksSpider;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Goutte\Client;

class BooksSpider implements ShouldQueue
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
            $this->saveBook($bxwx_books_spider);
        }
    }

    protected function saveBook(BxwxBooksSpider $bxwx_books_spider)
    {
        Books::create([
            'bxwx_id'      => $bxwx_books_spider->getUrl(),
            'author'       => $bxwx_books_spider->getAuthor(),
            'title'        => $bxwx_books_spider->getTitle(),
            'type'         => $bxwx_books_spider->getType(),
            'image'        => $bxwx_books_spider->getImage(),
            'introduction' => $bxwx_books_spider->getIntroduction(),
            'is_ending'    => $bxwx_books_spider->getIsEnding(),
        ]);
    }
}
