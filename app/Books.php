<?php

namespace App;

use App\Libraries\EsSearchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Books extends Model
{
    /**
     * Class Post
     * @package App
     * @property string  $bxwx_id
     * @property string  $author
     * @property integer $type
     * @property string  $title
     * @property string  $image
     * @property string  $introduction
     * @property integer $read_count
     * @property integer $is_ending
     * @property string  $created_at
     * @property string  $updated_at
     */
    use SoftDeletes;

    use Searchable, EsSearchable;

    protected $table = 'books';

    protected $fillable = ['bxwx_id', 'title', 'author', 'type', 'image', 'introduction', 'read_count', 'is_ending'];


    /**
     *  所属类型
     */
    const XH = 1;
    const XZ = 2;
    const DS = 3;
    const LS = 4;
    const WY = 5;
    const KH = 6;
    const KB = 7;
    const QB = 8;

    public static function typeMap($key = null)
    {
        $items = [
            self::XH => '玄幻小说',
            self::XZ => '修真小说',
            self::DS => '都市小说',
            self::LS => '历史小说',
            self::WY => '网游小说',
            self::KH => '科幻小说',
            self::KB => '恐怖小说',
            self::QB => '全本小说',
        ];
        return get_items($items, $key);
    }


    const LZ = 1;
    const WB = 2;

    public static function endingMap($key = null)
    {
        $items = [
            self::LZ => '连载中',
            self::WB => '完结',
        ];
        return get_items($items, $key);
    }

    /*
     * 搜索的type
     */
    public function searchableAs()
    {
        return 'books';
    }

    public function toSearchableArray()
    {
        return [
            'title'        => $this->title,
            'author'       => $this->author,
        ];
    }

}
