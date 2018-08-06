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

    /**
     * Get by xpath array
     *
     * @param array $xpath
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function xpath(array $xpath)
    {
        return $this->xpaths()->where('xpath', implode(Xpath::DELIMITER, $xpath));
    }
}
