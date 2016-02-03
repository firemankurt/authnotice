<?php namespace KurtJensen\AuthNotice\Models;

use Model;

/**
 * MessageMax Model
 */
class MessageMax extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'kurtjensen_authnotice_message_maxes';

    public $timestamps = false;

    protected $primaryKey = 'plugin';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['max_id', 'plugin'];
}
