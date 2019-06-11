<?php namespace App\Libraries;

use App\Books;
use Goutte\Client;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BxwxBooksSpider
 * @package App\Libraries
 */
class BxwxBooksSpider
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
    public function getTitle()
    {
        return trim($this->crawler->filter('#maininfo > #info > h1')->text());
    }

    /**
     * @return string
     */
    public function getType()
    {
        $data = 0;
        $type = $this->crawler->filter('.con_top')->text();
        $type_arr = explode('>', $type);
        $book_types = Books::typeMap();
        if (in_array(trim($type_arr[1]), $book_types)) {
            $data = array_flip($book_types)[trim($type_arr[1])];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        $author = trim($this->crawler->filterXPath('//*[@id="info"]/p[1]')->text());
        $author_arr = explode('：', $author);
        return trim($author_arr[1]);
    }

    /**
     * @return string
     */
    public function getIntroduction()
    {
        return trim($this->crawler->filter('#intro > p')->nextAll()->text());
    }

    /**
     * @return string
     */
    public function getIsEnding()
    {
        $data = 0;
        $html = $this->crawler->html();
        preg_match('/<meta property=\"og:novel:status\" content=".*?"/', $html, $ending);
        $status = explode('=', $ending[0]);
        $book_end_types = Books::endingMap();
        $status_end = explode('"', $status[2])[1];
        if (in_array($status_end, $book_end_types)) {
            $data = array_flip($book_end_types)[$status_end];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        $img_url = $this->crawler->filter('#fmimg img')->image()->getUri();
        //$disk = Storage::disk('oss');
        //$disk->put('books/filename.jpg', public_path().'/books/23_23690/23690s.jpg');
        //$disk->put('books/filename.jpg', $img_url);
        $ima_path = explode('/', parse_url($img_url)['path']);

        if (count($ima_path) > 5){
            $save_url = 'books/' . $ima_path[4] . '_' . $ima_path[5];
        }else{
            $save_url = 'books/' . $ima_path[4];
        }
        //dd($save_url);
        if ($result = download($img_url, $save_url)) {
            return $result;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}