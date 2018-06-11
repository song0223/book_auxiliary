<?php namespace App\Libraries;

use App\Books;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BxwxZjSpider
 * @package App\Libraries
 */
class BxwxZjSpider
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
    public function getZjText()
    {
        return $this->crawler->filter('#content')->html();
    }

    /**
     * @return string
     */
    public function getZjTitle()
    {
        return $this->crawler->filter('.bookname h1')->text();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}