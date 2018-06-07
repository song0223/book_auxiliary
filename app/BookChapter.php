<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookChapter extends Model
{
    use SoftDeletes;

    protected $table = 'books_chapter';

    protected $fillable = ['book_id', 'title', 'content'];

}
