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

    protected $fillable = ['book_id', 'bxwx_id', 'title', 'content', 'sort'];


    public function toSearchableArray()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
        ];
    }

    public function book()
    {
        return $this->hasOne(Books::class, 'id', 'book_id');
    }


    public function getChapterByBookId($book_id)
    {
        return $this->where('book_id', $book_id)->select(['id', 'title'])->orderBy('sort', 'asc')->get();
    }

    /**
     * 上一章
     * @param $bxwx_id
     * @return mixed
     */
    public function getPrevArticleId($book_id, $bxwx_id)
    {
        return BookChapter::where('bxwx_id', '<', $bxwx_id)->where('book_id', $book_id)->max('id');
    }

    public function getNextArticleId($book_id, $bxwx_id)
    {
        return BookChapter::where('bxwx_id', '>', $bxwx_id)->where('book_id', $book_id)->min('id');
    }
}
