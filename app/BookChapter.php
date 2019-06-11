<?php

namespace App;

use App\Libraries\EsSearchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class BookChapter extends Model
{
    use SoftDeletes;

    use Searchable, EsSearchable;

    protected $table = 'book_chapter';

    protected $fillable = ['id', 'book_id', 'bxwx_id', 'bxwx_url', 'title', 'content', 'sort'];

    public function searchableAs()
    {
        return "book_chapter";
    }

    public function toSearchableArray()
    {
        return [
            'title'   => $this->title,
            'book_id' => $this->book_id,
            'content' => $this->content,
        ];
    }

    public function book()
    {
        return $this->hasOne(Books::class, 'id', 'book_id');
    }


    public function getChapterByBookId($book_id)
    {
        return $this->where('book_id', $book_id)->select(['id', 'title'])->orderBy('bxwx_id', 'asc')->get();
    }

    /**
     * 下一章
     * @param $bxwx_id
     * @param $book_id
     * @return mixed
     */
    public function getNextArticleId($bxwx_id, $book_id)
    {
        return BookChapter::where('id', '>', intval($bxwx_id))->where('book_id', $book_id)->min('id');
    }

    public function getPrevArticleId($bxwx_id, $book_id)
    {
        return BookChapter::where('id', '<', intval($bxwx_id))->where('book_id', $book_id)->max('id');
    }
}
