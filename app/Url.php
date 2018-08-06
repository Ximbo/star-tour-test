<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Url
 * @package App
 *
 * @property int $id
 * @property string $url
 */
class Url extends Model
{
    const TABLE = 'urls';

    /** @var string */
    protected $table = self::TABLE;

    /** @var array */
    protected $fillable = ['url'];

    /** @var bool */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function xpaths()
    {
        return $this->hasMany(Xpath::class, 'url_id', 'id');
    }
}
