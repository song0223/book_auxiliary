<?php

namespace App;

use App\Libraries\EsSearchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
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

    //use Searchable, EsSearchable;

    protected $table = 'books';

    protected $fillable = ['bxwx_id', 'bxwx_url', 'title', 'author', 'type', 'image', 'introduction', 'read_count', 'is_ending'];


    /**
     *  所属类型
     */
    const XH = 1;
    const WX = 2;
    const YQ = 3;
    const LS = 4;
    const YX = 5;
    const Ly = 6;

    public static function typeMap($key = null)
    {
        $items = [
            self::XH => '奇幻玄幻',
            self::WX => '武侠修真',
            self::YQ => '都市言情',
            self::LS => '历史军事',
            self::YX => '网游竞技',
            self::Ly => '科幻异灵',
        ];
        return get_items($items, $key);
    }

    public static function dQTypeMap($key = null)
    {
        $items = [
            self::XH => '奇幻小说、玄幻小说大全列表',
            self::WX => '武侠小说、仙侠小说、修真小说大全列表',
            self::YQ => '言情小说、都市小说大全列表',
            self::LS => '历史小说、军事小说、穿越小说大全列表',
            self::YX => '游戏小说、竞技小说、网游小说大全列表',
            self::Ly => '异灵小说、科幻小说大全列表',
        ];
        return get_items($items, $key);
    }

    public static function redisMap($key = null)
    {
        $items = [
            self::XH => 10,
            self::WX => 11,
            self::YQ => 12,
            self::LS => 13,
            self::YX => 14,
            self::Ly => 15,
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
            'bxwx_id'       => $this->bxwx_id,
        ];
    }

    public function getImageAttribute($value)
    {
        return ($value)?Storage::disk('oss')->signUrl($value,3600):'';
    }
}
