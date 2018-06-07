<?php

namespace App\Console\Commands;

use App\Books;
use App\Libraries\BooksUrlSpider;
use App\Libraries\BxwxBooksSpider;
use Goutte\Client;
use Illuminate\Console\Command;

class ImportBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();

        $url = 'http://www.biquge.com.tw/paihangbang/allvote.html';

        $books_url_spider = new BooksUrlSpider($client, $url);
        $books_urls = $books_url_spider->getBookUrl();
        foreach ($books_urls as $url) {
            $arr = parse_url($url);
            $bxwx_id = trim($arr['path'], '/');
            if (Books::where('bxwx_id', $bxwx_id)->exists()) {
                continue;
            }
            $bxwx_books_spider = new BxwxBooksSpider($client, $url);
            $this->saveBook($bxwx_books_spider, $bxwx_id);
            $this->info($bxwx_books_spider->getTitle() . '：录入完成!');
        }
        $this->info('全部录入完成!');
    }

    protected function saveBook(BxwxBooksSpider $bxwx_books_spider, $bxwx_id)
    {
        Books::create([
            'bxwx_id'      => $bxwx_id,
            'author'       => $bxwx_books_spider->getAuthor(),
            'title'        => $bxwx_books_spider->getTitle(),
            'type'         => $bxwx_books_spider->getType(),
            'image'        => $bxwx_books_spider->getImage(),
            'introduction' => $bxwx_books_spider->getIntroduction(),
            'is_ending'    => $bxwx_books_spider->getIsEnding(),
            'read_count'   => 0,
        ]);
    }
}
