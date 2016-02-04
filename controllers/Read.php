<?php namespace KurtJensen\AuthNotice\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use KurtJensen\AuthNotice\Classes\Retrieve as Retriever;
use KurtJensen\AuthNotice\Models\Settings;

/**
 * Read Back-end Controller
 */
class Read extends Controller
{
    use \KurtJensen\AuthNotice\Traits\Purge;

    public $requiredPermissions = ['kurtjensen.authnotice.read'];

    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
    ];

    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('KurtJensen.AuthNotice', 'authnotice', 'read');
    }

    public function listExtendQuery($query)
    {
        return $query->WhereNotNull('source')->UnRead();
    }

    /**
     * Makes a request to the plugin authors message service
     */
    public function update($id)
    {
        parent::update($id);
        $this->vars['lang'] = Settings::get('read_lang', 'en');
    }

    public function onRetrieve()
    {
        $retriever = new Retriever();
        $retriever->Retrieve();
        Flash::success('Finished retrieving messages.');
        return $this->listRefresh();
    }

    /**
     * Makes a request to the plugin authors message service
     */
    public function onMarkRead($id)
    {
        if (post('id') && post('markread')) {
            if (!$message = Message::find($id)) {
                return;
            }
            $message->markRead();
        }
    }

    public function index_onRead()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            Message::whereIn('id', $checkedIds)
                ->update(['read' => 1]);

            Flash::success('Successfully marked those messages.');
        }

        return $this->listRefresh();
    }

    public function index_onPurge()
    {
        $this->Purge();

        Flash::success('Successfully deleted read messages upto purge date.');

        return $this->listRefresh();
    }

}
