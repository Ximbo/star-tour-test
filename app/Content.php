<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Content
 * @package App
 *
 * @property int $id
 * @property int $xpath_id
 * @property string $content
 */
class Content extends Model
{
    const TABLE = 'contents';

    /** @var string */
    protected $table = self::TABLE;

    /** @var array */
    protected $fillable = ['xpath_id', 'content'];

    /** @var bool */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function xpath()
    {
        return $this->belongsTo(Xpath::class);
    }
}
