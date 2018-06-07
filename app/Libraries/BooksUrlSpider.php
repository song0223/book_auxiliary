<?php namespace App\Libraries;

use Goutte\Client;
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
     * WechatPostSpider constructor.
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
        return $this->crawler->filter('#main > .box ul > li > a')->each(function ($node) {
            $data = $node->link()->getUri();
            return $data;
        });
    }
}