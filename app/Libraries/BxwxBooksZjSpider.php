<?php namespace App\Libraries;

use App\Books;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BxwxBooksZjSpider
 * @package App\Libraries
 */
class BxwxBooksZjSpider
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
     * @return string
     */
    public function getZjUrl()
    {
        return $this->crawler->filter('#list > dl > dd > a')->each(function ($node) {
            $data = $node->link()->getUri();
            return $data;
        });
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}