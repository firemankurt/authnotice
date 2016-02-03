<?php namespace KurtJensen\AuthNotice\Components;

use Cms\Classes\ComponentBase;
use KurtJensen\AuthNotice\Models\Message;

class Service extends ComponentBase
{
    /**
     * @var KurtJensen\Mailgun\Models\Mess The message model used for display.
     */
    public $messages;

    public function componentDetails()
    {
        return [
            'name' => 'Service',
            'description' => 'Message out content for your plugins.',
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'Slug',
                'description' => 'Slug for plugin id.',
                'default' => '{{ :slug }}',
                'type' => 'string',
            ],
            'last_id' => [
                'title' => 'Slug',
                'description' => 'Slug for plugin id.',
                'default' => '{{ :last_id }}',
                'type' => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $plugin = $this->property('slug', null);
        $last_id = $this->property('last_id', null);
        return json_encode($this->loadMessages($plugin, $last_id));
    }

    protected function loadMessages($plugin, $last_id)
    {
        if (is_null($plugin) || is_null($last_id)) {
            return false;
        }
        $messages = Message::where('plugin', $plugin)->
        where('id', '>', $last_id)->
        where('send', 1)->
        whereNull('mess_id')->
        get();
        return $messages;
    }

}
