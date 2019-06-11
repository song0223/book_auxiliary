<?php

namespace App\Console\Commands;

use App\BookChapter;
use App\Libraries\BxwxBooksZjSpider;
use App\Libraries\BxwxZjSpider;
use Goutte\Client;
use App\Books;
use Illuminate\Console\Command;

class ImportZj extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:zj';

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
        $books = Books::where('type', Books::XH)->get();
        foreach ($books as $book) {
            $bxwx_books_zj_spider = new BxwxBooksZjSpider($client, $book->bxwx_url);
            $book_zj_urls = $bxwx_books_zj_spider->getZjUrl();
            $this->info('小说：' . $book->title . '：开始录入!');
            foreach ($book_zj_urls as $book_zj_url) {
                $arr = parse_url($book_zj_url);
                $zj_id = explode('/', $arr['path']);
                $zj_id = explode('.', $zj_id[3]);
                if (BookChapter::where('bxwx_id', $zj_id[0])->exists()) {
                    continue;
                }
                $bxwx_zj_spider = new BxwxZjSpider($client, $book_zj_url);
                $this->saveZj($bxwx_zj_spider, $book->id, $zj_id[0]);
                $this->info($bxwx_zj_spider->getZjTitle() . '：录入完成!');
            }
            $this->info('小说：' . $book->title . '：录入完成!');
        }
        $this->info('：录入结束!');
    }

    protected function saveZj(BxwxZjSpider $bxwx_zj_spider, $book_id, $bxwx_id)
    {
        $a = preg_replace("/<p\>.*?<\/p>/", '', $bxwx_zj_spider->getZjText());
        $a = str_replace('(三七中文 et)', '', $a);
        $a = str_replace('    一秒记住【乐文 .la】，为您提供精彩阅读。', '', $a);
        BookChapter::create([
            'book_id'  => $book_id,
            'bxwx_id'  => $bxwx_id,
            'bxwx_url' => $bxwx_zj_spider->getUrl(),
            'title'    => trim($bxwx_zj_spider->getZjTitle()),
            'content'  => $a,
            'sort'     => $bxwx_id,
        ]);
    }
}
