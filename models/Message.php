<?php namespace KurtJensen\AuthNotice\Models;

use Carbon\Carbon;
use KurtJensen\AuthNotice\Models\Settings;
use Model;

/**
 * Message Model
 */
class Message extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'kurtjensen_authnotice_messages';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'id',
        'mess_id',
        'plugin',
        'author',
        'level',
        'text',
        'send',
        'read',
        'sent_at',
        'source',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['sent_at'];

    public function getPluginOptions()
    {
        $plugins = explode(',', Settings::get('plugins', ''));

        if (!count($plugins)) {return ['' => ''];}

        foreach ($plugins as $plugin) {
            $list[$plugin] = trim($plugin);
        }
        return $list;
    }

    public function scopeRead($query)
    {
        return $query->where('read', 1);
    }

    public function scopeUnRead($query)
    {
        return $query->where('read', 0);
    }

    public function beforeSave()
    {
        if ($this->send && !$this->sent_at) {
            $this->sent_at = Carbon::now();
        };
    }

    public function markRead()
    {
        $this->read = 1;
        $this->save();

    }

}
