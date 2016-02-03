<?php namespace KurtJensen\AuthNotice\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use DB;
use Flash;
use KurtJensen\AuthNotice\Models\Message;
use KurtJensen\AuthNotice\Models\MessageMax;
use October\Rain\Network\Http;
use System\Classes\PluginManager;

/**
 * Messages Back-end Controller
 */
class Messages extends Controller
{
    public $plugin = null;
    public $url = '';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['kurtjensen.authnotice.*'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('KurtJensen.AuthNotice', 'authnotice', 'messages');
/*
$notDelete = DB::table('kurtjensen_authnotice_messages')
->select('plugin', DB::raw('max(mess_id) as max_id'))
->groupBy('plugin')
->get();

$query = DB::table('kurtjensen_authnotice_messages')
->whereRead(true)
$query->whereNotIn('id', function ($query) use ($row) {
DB::table('kurtjensen_authnotice_messages')
->select('id')
->where('plugin', $row->plugin)
->where('mess_id', $row->max_id);

$this->vars['m'] = $query->get();

 */

//[plugin] => KurtJensen.Mailgun [max_id] => 78
    }

    public function index()
    {

        parent::index();

        $protectRows = MessageMax::lists('row_id');

        $this->vars['m'] = Message::whereNotIn('id', $protectRows)
             ->where('read', 1)->lists('id');
/**
$this->vars['m'] = Message::select('plugin', DB::raw("id, max(mess_id) as m"))
->groupBy('plugin')
->get()->toArray();
 **/
/*
$this->vars['m'] = Db::table('kurtjensen_authnotice_messages');

->where('name', '=', 'John')
->orWhere(function ($query) {
$query->where('votes', '>', 100)
->where('title', '<>', 'Admin');
})
->get();

 */
    }

    public function listExtendQuery($query)
    {
        return $query->where('source', '')->orWhereNull('source');
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $messageId) {
                if (!$message = Message::find($messageId)) {
                    continue;
                }

                $message->delete();
            }

            Flash::success('Successfully deleted those messages.');
        }

        return $this->listRefresh();
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

    public function onTest()
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

    public function doRequests($serviceUrls)
    {
        /*
        $maxMessages = DB::table('kurtjensen_authnotice_messages')
        ->whereIn('plugin', array_keys($serviceUrls))
        ->select('plugin', DB::raw('max(mess_id) as max_id'))
        ->groupBy('plugin')
        ->get();
         */
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
                                 //'author' => ,
                                 //'level' => ,
                                 //'text' => ,
                                 //'send' => ,
                'read' => false,
                //'sent_at' => ,
                'source' => $this->url,
                                 //'created_at' => ,
                'updated_at' => null,
            ]);

        return Message::create($newMessage);
    }
}
