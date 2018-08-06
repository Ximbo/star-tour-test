<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Xpath
 * @package App
 *
 * @property int $id
 * @property int $url_id
 * @property string $xpath
 */
class Xpath extends Model
{
    const TABLE = 'xpaths';
    const DELIMITER = '::';

    /** @var string */
    protected $table = self::TABLE;

    /** @var array */
    protected $fillable = ['url_id', 'xpath'];

    /** @var bool */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contents()
    {
        return $this->hasMany(Content::class, 'xpath_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    /**
     * @return array
     */
    public function getXpaths(): array
    {
        return explode(self::DELIMITER, (string) $this->xpath);
    }

    /**
     * @param array $xpaths
     * @return $this
     */
    public function setXpaths(array $xpaths)
    {
        $this->xpath = implode(self::DELIMITER, $xpaths);
        return $this;
    }
}
