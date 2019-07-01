<?php

namespace App\Console\Commands;

use App\Books;
use App\Libraries\BooksUrlSpider;
use App\Libraries\BxwxBooksSpider;
use App\Service\OSS;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

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

        $url = 'http://www.xbiquge.la/xiaoshuodaquan/';
        $redis = Redis::connection();

        //获取全部小说存入redis
        /*$books_url_spider = new BooksUrlSpider($client, $url);
        $books_urls = $books_url_spider->getBookUrl();
        foreach ($books_urls as $k => $book) {
            Redis::select($k+10);
            foreach ($book as $v){
                foreach ($v as $a){
                    $redis->set($a['title'], $a['url']);
                }
            }
        }*/
        /*$value = 'books/BrxCAB2D4DRGkUQ9mAkSocKENMbKhJ58AlIwhY1f.jpeg';
        $a = ($value)?Storage::disk('oss')->get($value,3600):'';*/
        Redis::select(Books::redisMap(Books::YX));//奇幻小说、玄幻小说库
        $books_urls = Redis::command('keys', ['*']);
        foreach ($books_urls as $url) {
            $url1 = $redis->get($url);
            $arr = parse_url($url1);
            $bxwx_id = trim($arr['path'], '/');
            if (Books::where('bxwx_id', $bxwx_id)->exists()) {
                continue;
            }
            $bxwx_books_spider = new BxwxBooksSpider($client, $url1);
            $this->saveBook($bxwx_books_spider, $bxwx_id, $url);
            $this->info($url . '：录入完成!');
        }
        $this->info('全部录入完成!');
    }

    protected function saveBook(BxwxBooksSpider $bxwx_books_spider, $bxwx_id, $title)
    {
        Books::create([
            'bxwx_id'      => $bxwx_id,
            'author'       => $bxwx_books_spider->getAuthor(),
            'title'        => $title,
            'type'         => Books::YX,
            'bxwx_url'     => $bxwx_books_spider->getUrl(),
            'image'        => $bxwx_books_spider->getImage(),
            'introduction' => $bxwx_books_spider->getIntroduction(),
            'is_ending'    => 1,
            'read_count'   => 0,
        ]);
    }
}
