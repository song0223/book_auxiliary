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
}
