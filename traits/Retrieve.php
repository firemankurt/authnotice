<?php namespace KurtJensen\AuthNotice\Traits;

use KurtJensen\AuthNotice\Models\Message;
use KurtJensen\AuthNotice\Models\MessageMax;

trait Retrieve
{

    public function onRetrieve()
    {

        // $plugins = System\Models\PluginVersion::get('code');
        $serviceUrls = [];
        $manager = PluginManager::instance();
        $plugins = $manager->getPlugins();

        foreach ($plugins as $authPlug => $plugin) {
            $details = $plugin->pluginDetails();

            if (isset($details['message_url'])) {
                $serviceUrls[$authPlug] = $details['message_url'];
            }
        }
        $this->doRequests($serviceUrls);
        return ['#response' => 'done'];
    }

    /**
     * Makes a request to the plugin authors message service
     */
    public function request($last_id)
    {
        $last_id = $last_id ? $last_id : 0;
        //echo $this->url . $this->plugin . '/' . $last_id . '<br />';
        //return;
        $response = Http::get($this->url . $this->plugin . '/' . $last_id, function ($http) {
            $http->setOption(CURLOPT_FORBID_REUSE, true);
            $http->header('User-Agent', 'KurtJensen-AuthNotice/V1');
            $http->noRedirect();
            $http->timeout(3600);
        });
        return $this->processResponse($response);
    }

    public function processResponse($response)
    {
        //die($response->body);
        $responseCode = $response->code;
        switch ($responseCode) {
            case 200:
                return $this->insertMessages(json_decode($response->body, true));
                break;
            case 404:
                $errorType = 'Invalid URL, NOT FOUND';
                break;
            default:
                $errorType = 'Unknown type HTTP ERROR';

        }
        return [];
    }

    public function doRequests($serviceUrls)
    {
        $maxMessIds = MessageMax::lists('max_id', 'plugin');

        foreach ($serviceUrls as $plugin => $url) {
            $this->plugin = $plugin;
            $this->url = $url;
            $this->request(array_get($maxMessIds, $plugin, 0));
        }
    }

    public function insertMessages($data)
    {
        //var_dump($data);
        $HighestId = $rowId = 0;
        if (!is_array($data) || count($data) <= 0) {
            return;
        }

        foreach ($data as $message) {
            if (!is_array($message)) {
                return;
            }
            $msg = $this->insertNewMessage($message);
            // Find Highest Id in all messages
            if ($HighestId < $message['id']) {
                $HighestId = $message['id'];
                $rowId = $msg->id;
            }
        }
        // Update if there is new Highest ID
        if ($HighestId > 0) {
            $messMax = MessageMax::where('plugin', $this->plugin)->first();
            if (!$messMax) {
                $messMax = new MessageMax;
                $messMax->plugin = $this->plugin;
            }
            $messMax->max_id = $HighestId;
            $messMax->row_id = $rowId;
            $messMax->save();
        }
    }

    public function insertNewMessage($message)
    {
        $id = $message['id'];
        unset($message['id']);
        $newMessage = array_merge($message,
            [
                'mess_id' => $id,
                'plugin' => $this->plugin,
/**
 *               'author' => ,
 *               'level' => ,
 *               'text' => ,
 *               'send' => ,
 **/
                'read' => false,
/**             'sent_at' => , **/
                'source' => $this->url,
//**            'created_at' => ,  **/
                'updated_at' => null,
            ]);

        return Message::create($newMessage);
    }
}
