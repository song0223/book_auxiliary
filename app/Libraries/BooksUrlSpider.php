<?php namespace App\Libraries;

use Goutte\Client;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BooksUrlSpider
 * @package App\Libraries
 */
class BooksUrlSpider
{

    /**
     * @var Crawler|null
     */
    protected $crawler;

    /**
     * @var string
     */
    protected $url;

    /**
     * BooksUrlSpider constructor.
     * @param Client $client
     * @param        $url
     */
    public function __construct(Client $client, $url)
    {
        $this->url = $url;
        $this->crawler = $client->request('GET', $url);
    }

    /**
     * 获取排行榜小说url
     * @return array
     */
    public function getBookUrl()
    {
        $data = [];
        return $this->crawler->filter('.novellist')->each(function ($node) use ($data){
            $type = $node->filter('h2')->html();
            $data[$type] = $node->filter('ul > li')->each(function ($n) use ($data, $type){
                $d['title'] = $n->filter('a')->html();
                $d['url'] = $n->filter('a')->link()->getUri();
                return $d;
            });
            //dd($data);
            //$data = $node->link()->getUri();
            return $data;
        });
    }
}